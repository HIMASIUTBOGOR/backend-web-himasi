<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function signIn(Request $request)
    {
        $request->validate([
            'nim' => 'required|string',
            'password' => 'required|string|min:6'
        ]);

        if (!auth()->attempt($request->only('nim', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = auth()->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Sign in successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function signOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sign out successful'
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $userWithRelations = User::with('roles.permissions')
            ->find($user->id);

        $allPermissions = $userWithRelations->getAllPermissions();
        $allRoles = $userWithRelations->getRoleNames();

        $permNames = $allPermissions->pluck('name');

        // Build hierarchical menus filtered by user permissions; include public menus (permission_name null)
        $menus = Menu::whereNull('parent_id')
            ->with(['children' => function ($q) use ($permNames) {
                $q->where(function ($qq) use ($permNames) {
                    $qq->whereIn('permission_name', $permNames)
                       ->orWhereNull('permission_name');
                })
                ->orderBy('order');
            }])
            ->where(function ($q) use ($permNames) {
                $q->whereIn('permission_name', $permNames)
                  ->orWhereNull('permission_name');
            })
            ->orderBy('order')
            ->get();

        return response()->json([
            'user' => $user,
            'roles' => $allRoles,
            'permissions' => $allPermissions->pluck('name')->toArray(),
            'menus' => $menus
        ]);
    }


    public function roles()
    {
        return Role::with('permissions')->get();
    }

    public function permissions()
    {
        return Permission::all();
    }

    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string'
        ]);

        $user = User::findOrFail($userId);
        $user->syncRoles([$request->role]);

        return response()->json([
            'message' => 'Role assigned',
            'user' => $user->load('roles')
        ]);
    }

    public function assignPermission(Request $request, $roleId)
    {
        $request->validate([
            'permissions' => 'required|array'
        ]);

        $role = Role::findById($roleId, 'api');
        $role->syncPermissions($request->permissions);

        return response()->json([
            'message' => 'Permissions updated',
            'role' => $role->load('permissions')
        ]);
    }

    public function assignMenuPermission(Request $request, $roleId)
{
    $role = Role::findById($roleId, 'api');
    $role->syncPermissions($request->permissions);

    return response()->json([
        'message' => 'Menu permission updated'
    ]);
}


    public function myMenus(Request $request)
{
    $user = $request->user();

    $menus = Menu::whereNull('parent_id')
        ->with(['children' => function ($q) use ($user) {
            $q->whereIn('permission_name', $user->getAllPermissions()->pluck('name'));
        }])
        ->whereIn('permission_name', $user->getAllPermissions()->pluck('name'))
        ->orderBy('order')
        ->get();

    return response()->json($menus);
}

}
