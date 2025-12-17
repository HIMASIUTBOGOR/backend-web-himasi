<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class UserManagementController extends Controller
{

    public function users()
    {
        $users = User::with('roles.permissions')->orderBy('updated_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => UserResource::collection($users)
        ],200);
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:50|unique:users,nim',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|integer|exists:roles,id',
            'jabatan_id' => 'nullable|exists:enumerations,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240' // 10MB
        ]);

        if($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $request->merge(['avatar' => $avatarPath]);
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'nim' => $request->nim,
                'jabatan_id' => $request->jabatan_id,
                'avatar' => $request->avatar ?? null,
                'password' => bcrypt($request->password)
            ]);

            // Assign role to user
            if ($request->has('role_id')) {
                $role = \Spatie\Permission\Models\Role::findOrFail($request->role_id);
                $user->assignRole($role);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => $user->load('roles', 'permissions')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:50|unique:users,nim,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|integer|exists:roles,id',
            'jabatan_id' => 'nullable|exists:enumerations,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240' // 10MB
        ]);

        if($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $request->merge(['avatar' => $avatarPath]);
        }

        try {
            DB::beginTransaction();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->nim = $request->nim;
            $user->jabatan_id = $request->jabatan_id;
            if ($request->has('avatar')) {
                $user->avatar = $request->avatar;
            }
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }
            $user->save();

            // Sync role to user
            if ($request->has('role_id')) {
                $role = \Spatie\Permission\Models\Role::findOrFail($request->role_id);
                $user->syncRoles([$role]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => $user->load('roles', 'permissions')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ], 200);
    }

    public function permissions()
    {
        $data = Permission::all();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'guard_name' => 'required'
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Permission created successfully',
            'data' => $permission
        ], 201);
    }

    public function updatePermission(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
            'guard_name' => 'required'
        ]);

        $permission->name = $request->name;
        $permission->guard_name = $request->guard_name;
        $permission->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Permission updated successfully',
            'data' => $permission
        ]);
    }

    public function deletePermission($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Permission deleted successfully'
        ], 200);
    }


    public function roles()
    {
        $roles = \Spatie\Permission\Models\Role::with('permissions')->get();
        return response()->json([
            'status' => 'success',
            'data' => $roles
        ]);
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => ['required', Rule::unique('roles', 'name')],
            'guard_name' => 'required',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id'
        ]);

        try {
            DB::beginTransaction();

            $role = \Spatie\Permission\Models\Role::create([
                'name' => $request->name,
                'guard_name' => $request->guard_name
            ]);

            // Attach permissions to role
            if ($request->has('permissions') && !empty($request->permissions)) {
                $role->permissions()->attach($request->permissions);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Role created successfully',
                'data' => $role->load('permissions')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create role: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateRole(Request $request, $id)
    {
        $role = \Spatie\Permission\Models\Role::findOrFail($id);

        $request->validate([
            'name' => ['required', Rule::unique('roles', 'name')->ignore($role->id)],
            'guard_name' => 'required',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id'
        ]);

        try {
            DB::beginTransaction();

            $role->name = $request->name;
            $role->guard_name = $request->guard_name;
            $role->save();

            // Sync permissions to role
            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Role updated successfully',
                'data' => $role->load('permissions')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update role: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteRole($id)
    {
        $role = \Spatie\Permission\Models\Role::findOrFail($id);
        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Role deleted successfully'
        ], 200);
    }

    public function menus()
    {
        // Assuming you have a Menu model to fetch menus
        $menus = Menu::with('children')->whereNull('parent_id')->orderBy('order')->get();

        return response()->json([
            'status' => 'success',
            'data' => $menus
        ], 200);
    }

    public function storeMenu(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:menus,id',
            'permission_name' => 'nullable|string',
            'order' => 'nullable|integer'
        ]);

        try {
            DB::beginTransaction();

            // Ensure permission exists if provided
            if ($request->filled('permission_name')) {
               
                Permission::firstOrCreate([
                    'name' => $request->permission_name,
                    'guard_name' => "api"
                ]);
            }

            $menu = Menu::create($request->all());

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Menu created successfully',
                'data' => $menu
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create menu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateMenu(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:menus,id',
            'permission_name' => 'nullable|string',
            'order' => 'nullable|integer'
        ]);

        try {
            DB::beginTransaction();

            $menu->update($validated);

            // Ensure permission exists if provided
            if ($request->filled('permission_name')) {
                Permission::firstOrCreate([
                    'name' => $request->permission_name,
                    'guard_name' => "api"
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Menu updated successfully',
                'data' => $menu
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update menu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteMenu($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Menu deleted successfully'
        ], 200);
    }
}