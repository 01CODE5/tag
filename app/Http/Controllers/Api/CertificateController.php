<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Get all certificates
     */
    public function index()
    {
        return response()->json(Certificate::with('resident')->get());
    }

    /**
     * Create a new certificate
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'reference_number' => 'required|string|unique:certificates',
            'purpose' => 'required|string',
            'remarks' => 'nullable|string',
            'status' => 'in:pending,approved,rejected',
            'date_requested' => 'required|date',
            'valid_until' => 'nullable|date',
            'claim_by' => 'nullable|string',
        ]);

        $certificate = Certificate::create($validated);

        return response()->json($certificate, 201);
    }

    /**
     * Get a specific certificate
     */
    public function show(Certificate $certificate)
    {
        return response()->json($certificate->load('resident'));
    }

    /**
     * Update a certificate
     */
    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'purpose' => 'string',
            'remarks' => 'nullable|string',
            'status' => 'in:pending,approved,rejected',
            'date_approved' => 'nullable|date',
            'date_claimed' => 'nullable|date',
            'valid_until' => 'nullable|date',
            'claim_by' => 'nullable|string',
        ]);

        $certificate->update($validated);

        return response()->json($certificate);
    }

    /**
     * Delete a certificate
     */
    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return response()->json([
            'message' => 'Certificate deleted successfully',
        ]);
    }

    /**
     * Get certificates by status
     */
    public function byStatus(Request $request)
    {
        $status = $request->query('status');

        if (!in_array($status, ['pending', 'approved', 'rejected'])) {
            return response()->json([
                'error' => 'Invalid status',
            ], 422);
        }

        return response()->json(
            Certificate::where('status', $status)->with('resident')->get()
        );
    }

    /**
     * Lookup a certificate by reference number or resident full name.
     * Public endpoint: /api/certificates/lookup?reference=... or ?name=...
     */
    public function lookup(Request $request)
    {
        $reference = $request->query('reference');
        $name = $request->query('name');

        if ($reference) {
            $cert = Certificate::with('resident')->where('reference_number', $reference)->first();
            if (!$cert) {
                return response()->json(['error' => 'Certificate not found'], 404);
            }
            return response()->json($cert);
        }

        if ($name) {
            $results = Certificate::with('resident')
                ->whereHas('resident', function ($q) use ($name) {
                    $q->where('fullname', 'like', "%{$name}%");
                })
                ->orderBy('date_requested', 'desc')
                ->get();

            if ($results->isEmpty()) {
                return response()->json(['error' => 'No certificates found for that name'], 404);
            }

            return response()->json($results);
        }

        return response()->json(['error' => 'Missing query parameter (reference or name)'], 422);
    }
}
