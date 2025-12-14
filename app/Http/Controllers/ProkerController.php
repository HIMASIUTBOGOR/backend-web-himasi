<?php

namespace App\Http\Controllers;

use App\Models\Proker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProkerController extends Controller
{
    /**
     * Display a listing of prokers.
     */
    public function index()
    {
        $prokers = Proker::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'List data prokers',
            'data' => $prokers
        ], 200);
    }

    /**
     * Store a newly created proker.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        // Upload photo
        $photo = $request->file('photo');
        $photoPath = $photo->store('prokers', 'public');

        $proker = Proker::create([
            'photo' => $photoPath,
            'title' => $request->title,
            'desc' => $request->desc
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Proker created successfully',
            'data' => $proker
        ], 201);
    }

    /**
     * Display the specified proker.
     */
    public function show($id)
    {
        $proker = Proker::find($id);

        if (!$proker) {
            return response()->json([
                'success' => false,
                'message' => 'Proker not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Proker detail',
            'data' => $proker
        ], 200);
    }

    /**
     * Update the specified proker.
     */
    public function update(Request $request, $id)
    {
        $proker = Proker::find($id);

        if (!$proker) {
            return response()->json([
                'success' => false,
                'message' => 'Proker not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        // Update photo if provided
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($proker->photo && Storage::disk('public')->exists($proker->photo)) {
                Storage::disk('public')->delete($proker->photo);
            }

            $photo = $request->file('photo');
            $photoPath = $photo->store('prokers', 'public');
            $proker->photo = $photoPath;
        }

        $proker->title = $request->title;
        $proker->desc = $request->desc;
        $proker->save();

        return response()->json([
            'success' => true,
            'message' => 'Proker updated successfully',
            'data' => $proker
        ], 200);
    }

    /**
     * Remove the specified proker.
     */
    public function destroy($id)
    {
        $proker = Proker::find($id);

        if (!$proker) {
            return response()->json([
                'success' => false,
                'message' => 'Proker not found'
            ], 404);
        }

        // Delete photo
        if ($proker->photo && Storage::disk('public')->exists($proker->photo)) {
            Storage::disk('public')->delete($proker->photo);
        }

        $proker->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proker deleted successfully'
        ], 200);
    }
}
