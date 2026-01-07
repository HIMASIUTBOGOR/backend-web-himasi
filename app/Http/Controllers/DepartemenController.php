<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DepartemenController extends Controller
{
    /**
     * Display a listing of departemens.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $search = $request->input('search', '');
        $departemens = Departemen::orderBy('created_at', 'desc')->
            when($search, function ($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%')
                             ->orWhere('desc', 'like', '%' . $search . '%');
            })
            ->paginate($limit);
    
        return response()->json([
            'success' => true,
            'message' => 'List data departemens',
            'data' => $departemens->items(),
            'meta' => [
                'current_page' => $departemens->currentPage(),
                'last_page' => $departemens->lastPage(),
                'per_page' => $departemens->perPage(),
                'total' => $departemens->total()
            ]
        ], 200);
    }

    /**
     * Store a newly created departemen.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'icon' => 'required|string',
            'title' => 'required|string|max:255',
            'desc' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $departemen = Departemen::create([
            'icon' => $request->icon,
            'title' => $request->title,
            'desc' => $request->desc
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Departemen created successfully',
            'data' => $departemen
        ], 201);
    }

    /**
     * Display the specified departemen.
     */
    public function show($id)
    {
        $departemen = Departemen::find($id);

        if (!$departemen) {
            return response()->json([
                'success' => false,
                'message' => 'Departemen not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Departemen detail',
            'data' => $departemen
        ], 200);
    }

    /**
     * Update the specified departemen.
     */
    public function update(Request $request, $id)
    {
        $departemen = Departemen::find($id);

        if (!$departemen) {
            return response()->json([
                'success' => false,
                'message' => 'Departemen not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'icon' => 'required|string',
            'title' => 'required|string|max:255',
            'desc' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $departemen->icon = $request->icon;
        $departemen->title = $request->title;
        $departemen->desc = $request->desc;
        $departemen->save();

        return response()->json([
            'success' => true,
            'message' => 'Departemen updated successfully',
            'data' => $departemen
        ], 200);
    }

    /**
     * Remove the specified departemen.
     */
    public function destroy($id)
    {
        $departemen = Departemen::find($id);

        if (!$departemen) {
            return response()->json([
                'success' => false,
                'message' => 'Departemen not found'
            ], 404);
        }

        // Delete icon
        if ($departemen->icon && Storage::disk('public')->exists($departemen->icon)) {
            Storage::disk('public')->delete($departemen->icon);
        }

        $departemen->delete();

        return response()->json([
            'success' => true,
            'message' => 'Departemen deleted successfully'
        ], 200);
    }

    public function landingIndex()
    {
        $departemens = Departemen::orderBy('title', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'List data departemens for landing page',
            'data' => $departemens
        ], 200);
    }
}
