<?php

namespace App\Http\Controllers;

use App\Http\Resources\cms\BenefitResource;
use App\Models\Benefit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BenefitController extends Controller
{
    /**
     * Display a listing of benefits.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $search = $request->input('search', '');
        $benefits = Benefit::orderBy('created_at', 'desc')
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%')
                             ->orWhere('desc', 'like', '%' . $search . '%');
            })
            ->paginate($limit);
        
        return response()->json([
            'success' => true,
            'message' => 'List data benefits',
            'data' => BenefitResource::collection($benefits->items()),
            'meta' => [
                'current_page' => $benefits->currentPage(),
                'last_page' => $benefits->lastPage(),
                'per_page' => $benefits->perPage(),
                'total' => $benefits->total()
            ]
        ], 200);
    }

    /**
     * Store a newly created benefit.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:102400',
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
        $photoPath = $photo->store('benefits', 'public');

        $benefit = Benefit::create([
            'photo' => $photoPath,
            'title' => $request->title,
            'desc' => $request->desc
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Benefit created successfully',
            'data' => $benefit
        ], 201);
    }

    /**
     * Display the specified benefit.
     */
    public function show($id)
    {
        $benefit = Benefit::find($id);

        if (!$benefit) {
            return response()->json([
                'success' => false,
                'message' => 'Benefit not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Benefit detail',
            'data' => $benefit
        ], 200);
    }

    /**
     * Update the specified benefit.
     */
    public function update(Request $request, $id)
    {
        $benefit = Benefit::find($id);

        if (!$benefit) {
            return response()->json([
                'success' => false,
                'message' => 'Benefit not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:102400',
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
            if ($benefit->photo && Storage::disk('public')->exists($benefit->photo)) {
                Storage::disk('public')->delete($benefit->photo);
            }

            $photo = $request->file('photo');
            $photoPath = $photo->store('benefits', 'public');
            $benefit->photo = $photoPath;
        }

        $benefit->title = $request->title;
        $benefit->desc = $request->desc;
        $benefit->save();

        return response()->json([
            'success' => true,
            'message' => 'Benefit updated successfully',
            'data' => $benefit
        ], 200);
    }

    /**
     * Remove the specified benefit.
     */
    public function destroy($id)
    {
        $benefit = Benefit::find($id);

        if (!$benefit) {
            return response()->json([
                'success' => false,
                'message' => 'Benefit not found'
            ], 404);
        }

        // Delete photo
        if ($benefit->photo && Storage::disk('public')->exists($benefit->photo)) {
            Storage::disk('public')->delete($benefit->photo);
        }

        $benefit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Benefit deleted successfully'
        ], 200);
    }

    public function landingIndex()
    {
        $benefits = Benefit::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'List data benefits for landing page',
            'data' => BenefitResource::collection($benefits)
        ], 200);
    }
}
