<?php

namespace App\Http\Controllers;

use App\Models\Aspiration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AspirationController extends Controller
{
    /**
     * Display a listing of aspirations.
     */
    public function index()
    {
        $aspirations = Aspiration::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'List data aspirations',
            'data' => $aspirations
        ], 200);
    }

    /**
     * Store a newly created aspiration.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'student_id' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $aspiration = Aspiration::create([
            'category' => $request->category,
            'full_name' => $request->full_name,
            'student_id' => $request->student_id,
            'title' => $request->title,
            'message' => $request->message
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Aspiration created successfully',
            'data' => $aspiration
        ], 201);
    }

    /**
     * Display the specified aspiration.
     */
    public function show($id)
    {
        $aspiration = Aspiration::find($id);

        if (!$aspiration) {
            return response()->json([
                'success' => false,
                'message' => 'Aspiration not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Aspiration detail',
            'data' => $aspiration
        ], 200);
    }

    /**
     * Update the specified aspiration.
     */
    public function update(Request $request, $id)
    {
        $aspiration = Aspiration::find($id);

        if (!$aspiration) {
            return response()->json([
                'success' => false,
                'message' => 'Aspiration not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'student_id' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $aspiration->category = $request->category;
        $aspiration->full_name = $request->full_name;
        $aspiration->student_id = $request->student_id;
        $aspiration->title = $request->title;
        $aspiration->message = $request->message;
        $aspiration->save();

        return response()->json([
            'success' => true,
            'message' => 'Aspiration updated successfully',
            'data' => $aspiration
        ], 200);
    }

    /**
     * Remove the specified aspiration.
     */
    public function destroy($id)
    {
        $aspiration = Aspiration::find($id);

        if (!$aspiration) {
            return response()->json([
                'success' => false,
                'message' => 'Aspiration not found'
            ], 404);
        }

        $aspiration->delete();

        return response()->json([
            'success' => true,
            'message' => 'Aspiration deleted successfully'
        ], 200);
    }
}


