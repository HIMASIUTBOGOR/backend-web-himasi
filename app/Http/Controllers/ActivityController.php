<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities.
     */
    public function index()
    {
        $activities = Activity::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'List data activities',
            'data' => $activities
        ], 200);
    }

    /**
     * Store a newly created activity.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'desc' => 'required|string|max:255',
            'upload_at' => 'required|date',
            'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Upload image
        $image = $request->file('image');
        $imagePath = $image->store('activities', 'public');

        $activity = Activity::create([
            'image' => $imagePath,
            'desc' => $request->desc,
            'upload_at' => $request->upload_at,
            'is_active' => $request->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Activity created successfully',
            'data' => $activity
        ], 201);
    }

    /**
     * Display the specified activity.
     */
    public function show($id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json([
                'success' => false,
                'message' => 'Activity not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Activity detail',
            'data' => $activity
        ], 200);
    }

    /**
     * Update the specified activity.
     */
    public function update(Request $request, $id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json([
                'success' => false,
                'message' => 'Activity not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'desc' => 'required|string|max:255',
            'upload_at' => 'required|date',
            'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update image if provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($activity->image && Storage::disk('public')->exists($activity->image)) {
                Storage::disk('public')->delete($activity->image);
            }

            $image = $request->file('image');
            $imagePath = $image->store('activities', 'public');
            $activity->image = $imagePath;
        }

        $activity->desc = $request->desc;
        $activity->upload_at = $request->upload_at;
        $activity->is_active = $request->is_active;
        $activity->save();

        return response()->json([
            'success' => true,
            'message' => 'Activity updated successfully',
            'data' => $activity
        ], 200);
    }

    /**
     * Remove the specified activity.
     */
    public function destroy($id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json([
                'success' => false,
                'message' => 'Activity not found'
            ], 404);
        }

        // Delete image
        if ($activity->image && Storage::disk('public')->exists($activity->image)) {
            Storage::disk('public')->delete($activity->image);
        }

        $activity->delete();

        return response()->json([
            'success' => true,
            'message' => 'Activity deleted successfully'
        ], 200);
    }
}
