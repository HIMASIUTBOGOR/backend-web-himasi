<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    /**
     * Display a listing of news.
     */
    public function index()
    {
        $news = News::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'List data news',
            'data' => $news
        ], 200);
    }

    /**
     * Store a newly created news.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'author' => 'required|string|max:255'
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
        $photoPath = $photo->store('news', 'public');

        $news = News::create([
            'photo' => $photoPath,
            'title' => $request->title,
            'desc' => $request->desc,
            'author' => $request->author
        ]);

        return response()->json([
            'success' => true,
            'message' => 'News created successfully',
            'data' => $news
        ], 201);
    }

    /**
     * Display the specified news.
     */
    public function show($id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'News detail',
            'data' => $news
        ], 200);
    }

    /**
     * Update the specified news.
     */
    public function update(Request $request, $id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'author' => 'required|string|max:255'
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
            if ($news->photo && Storage::disk('public')->exists($news->photo)) {
                Storage::disk('public')->delete($news->photo);
            }

            $photo = $request->file('photo');
            $photoPath = $photo->store('news', 'public');
            $news->photo = $photoPath;
        }

        $news->title = $request->title;
        $news->desc = $request->desc;
        $news->author = $request->author;
        $news->save();

        return response()->json([
            'success' => true,
            'message' => 'News updated successfully',
            'data' => $news
        ], 200);
    }

    /**
     * Remove the specified news.
     */
    public function destroy($id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News not found'
            ], 404);
        }

        // Delete photo
        if ($news->photo && Storage::disk('public')->exists($news->photo)) {
            Storage::disk('public')->delete($news->photo);
        }

        $news->delete();

        return response()->json([
            'success' => true,
            'message' => 'News deleted successfully'
        ], 200);
    }
}
