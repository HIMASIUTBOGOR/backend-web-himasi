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
}
