<?php

namespace App\Http\Controllers;

use App\Models\Enumeration;
use Illuminate\Http\Request;

class EnumerationController extends Controller
{
    public function enumerations(Request $request)
    {
        $key = $request->input('key');
        $data = Enumeration::where('key', $key)->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $search = $request->input('search', '');
        $enumerations = Enumeration::where('key', 'like', "%$search%")
            ->orWhere('value', 'like', "%$search%")
            ->paginate($limit);
        return response()->json([
            'status' => 'success',
            'data' => $enumerations->items(),
            'meta' => [
                'current_page' => $enumerations->currentPage(),
                'last_page' => $enumerations->lastPage(),
                'per_page' => $enumerations->perPage(),
                'total' => $enumerations->total(),
            ]
        ]);
    }
    public function storeEnumeration(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:enumerations,key',
            'value' => 'required|string',
        ]);

        $enumeration = Enumeration::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Enumeration created successfully',
            'data' => $enumeration
        ], 201);
    }

    public function updateEnumeration(Request $request, $id)
    {
        $enumeration = Enumeration::findOrFail($id);

        $validated = $request->validate([
            'key' => 'sometimes|required|string|unique:enumerations,key,' . $id,
            'value' => 'sometimes|required|string',
        ]);

        $enumeration->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Enumeration updated successfully',
            'data' => $enumeration
        ],200);
    }

    public function deleteEnumeration($id)
    {
        $enumeration = Enumeration::findOrFail($id);
        $enumeration->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Enumeration deleted successfully'
        ]);
    }
}
