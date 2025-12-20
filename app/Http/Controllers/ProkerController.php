<?php

namespace App\Http\Controllers;

use App\Http\Resources\cms\ProkerLandingResource;
use App\Http\Resources\cms\ProkerResource;
use App\Models\Departemen;
use App\Models\Proker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProkerController extends Controller
{
    /**
     * Display a listing of prokers.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $search = $request->input('search', '');
        $prokers = Proker::orderBy('created_at', 'desc')->when($search, function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                         ->orWhere('desc', 'like', '%' . $search . '%');
        })->paginate($limit);
        
        return response()->json([
            'success' => true,
            'message' => 'List data prokers',
            'data' => ProkerResource::collection($prokers->items()),
            'meta' => [
                'current_page' => $prokers->currentPage(),
                'last_page' => $prokers->lastPage(),
                'per_page' => $prokers->perPage(),
                'total' => $prokers->total()
            ]
        ], 200);
    }

    /**
     * Store a newly created proker.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'departemen_id' => 'required|exists:departemens,id',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:102400', // 100MB
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'action_link' => 'nullable',
            'is_active' => 'required|boolean'
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
            'desc' => $request->desc,
            'departemen_id' => $request->departemen_id,
            'action_link' => $request->action_link,
            'is_active' => $request->is_active,
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
            'departemen_id' => 'required|exists:departemens,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:102400', // 100MB
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'action_link' => 'nullable',
            'is_active' => 'required|boolean'
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
        $proker->departemen_id = $request->departemen_id;
        $proker->action_link = $request->action_link;
        $proker->is_active = $request->is_active;
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

    public function landingIndex()
    {
        $departement = Departemen::orderBy('title', 'asc')->with('prokers')->get();

        return response()->json([
            'success' => true,
            'message' => 'List of active prokers for landing page',
            'data' => ProkerLandingResource::collection($departement)
        ], 200);
    }
}
