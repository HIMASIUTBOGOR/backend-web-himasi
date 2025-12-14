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
    public function index()
    {
        $departemens = Departemen::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'List data departemens',
            'data' => $departemens
        ], 200);
    }

    /**
     * Store a newly created departemen.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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

        // Upload icon
        $icon = $request->file('icon');
        $iconPath = $icon->store('departemens', 'public');

        $departemen = Departemen::create([
            'icon' => $iconPath,
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
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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

        // Update icon if provided
        if ($request->hasFile('icon')) {
            // Delete old icon
            if ($departemen->icon && Storage::disk('public')->exists($departemen->icon)) {
                Storage::disk('public')->delete($departemen->icon);
            }

            $icon = $request->file('icon');
            $iconPath = $icon->store('departemens', 'public');
            $departemen->icon = $iconPath;
        }

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
}
