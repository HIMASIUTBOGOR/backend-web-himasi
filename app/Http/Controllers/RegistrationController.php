<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'nim' => 'required|string|size:10|unique:registrations,nim',
            'semester' => 'required|integer|min:1|max:14',
            'no_wa' => 'required|string|max:15',
            'department_id' => 'nullable|uuid|exists:departemens,id',
            'reason' => 'nullable|string',
        ]);

        $registration = Registration::create($validatedData);

        return response()->json([
            'message' => 'Registration successful',
            'data' => $registration
        ], 201);
    }
}
