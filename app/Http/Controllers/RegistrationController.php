<?php

namespace App\Http\Controllers;

use App\Http\Resources\RegistrationResource;
use App\Models\Registration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RegistrationController extends Controller
{

 public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $search = $request->query('search');

        $registrations = Registration::orderBy('created_at', 'desc')
            ->when($search, function ($query, $search) {
                return $query->where('fullname', 'like', '%' . $search . '%')
                             ->orWhere('nim', 'like', '%' . $search . '%');
            })
            ->paginate($limit);
        
        return response()->json([
            'success' => true,
            'message' => 'List data registrations',
            'data' => RegistrationResource::collection($registrations->items()),
            'meta' => [
                'current_page' => $registrations->currentPage(),
                'last_page' => $registrations->lastPage(),
                'per_page' => $registrations->perPage(),
                'total' => $registrations->total()
            ]
        ], 200);
    }

    public function destroy($id)
    {
        $registration = Registration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Registration not found'
            ], 404);
        } 

        $registration->delete();
        return response()->json([
            'success' => true,
            'message' => 'News deleted successfully'
        ], 200);
    }

     /**
     * Store a newly created registration.
     */

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

    /**
     * Export single registration to PDF.
     */
    public function exportPdf($id)
    {
        $registration = Registration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Registration not found'
            ], 404);
        }

        $pdf = Pdf::loadView('pdf.registration-single', compact('registration'));
        
        return $pdf->download('registration_' . $registration->nim . '.pdf');
    }

    /**
     * Export all registrations to PDF.
     */
    public function exportPdfAll()
    {
        $registrations = Registration::orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('pdf.registration-all', compact('registrations'));
        
        return $pdf->download('all_registrations_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }
}
