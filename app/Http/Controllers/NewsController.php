<?php

namespace App\Http\Controllers;

use App\Http\Resources\cms\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    /**
     * Display a listing of news.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $search = $request->query('search');

        $news = News::orderBy('created_at', 'desc')
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%')
                             ->orWhere('desc', 'like', '%' . $search . '%');
            })
            ->paginate($limit);
        
        return response()->json([
            'success' => true,
            'message' => 'List data news',
            'data' => NewsResource::collection($news->items()),
            'meta' => [
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
                'per_page' => $news->perPage(),
                'total' => $news->total()
            ]
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
            'published_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'category_id' => 'required|exists:enumerations,id',
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
            'slug' => \Str::slug($request->title),
            'desc' => $request->desc,
            'category_id' => $request->category_id,
            'author' => $request->author,
            'published_at' => $request->published_at,
            'is_active' => $request->is_active ?? false
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
            'data' => new NewsResource($news),
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
            'published_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'category_id' => 'required|exists:enumerations,id',
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
        $news->slug = \Str::slug($request->title);
        $news->author = $request->author;
        $news->category_id = $request->category_id;
        $news->published_at = $request->published_at;
        $news->is_active = $request->is_active ?? $news->is_active;
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

    public function landingIndex(Request $request)
    {
        $limit = $request->query('limit', 6);

        $news = News::where('is_active', true)
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate($limit)->get();

        return response()->json([
            'success' => true,
            'message' => 'List of active news for landing page',
            'data' => NewsResource::collection($news->items),
            'meta' => [
                'total' => $news->count(),
                'per_page' => $limit,
                'current_page' => 1,
                'last_page' => ceil($news->count() / $limit)
            ]
        ], 200);
    }
}
