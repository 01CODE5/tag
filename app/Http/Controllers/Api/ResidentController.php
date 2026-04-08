<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    /**
     * Get all residents
     */
    public function index()
    {
        return response()->json(Resident::with('user')->get());
    }

    /**
     * Create a new resident
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'user_id' => 'required|exists:users,id|unique:residents',
            'fullname' => 'required|string',
            'age' => 'required|integer|min:1',
            'contact' => 'nullable|string',
            'address' => 'required|string',
            // 'civil_status' => 'nullable|string',
            // 'occupation' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $resident = Resident::create($validated);

        return response()->json($resident, 201);
    }

    /**
     * Get a specific resident
     */
    public function show(Resident $resident)
    {
        return response()->json($resident->load('user', 'certificates'));
    }

    /**
     * Update a resident
     */
    public function update(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'fullname' => 'string',
            'age' => 'integer|min:1',
            'contact' => 'nullable|string',
            'address' => 'string',
            'civil_status' => 'nullable|string',
            'occupation' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $resident->update($validated);

        return response()->json($resident);
    }

    /**
     * Delete a resident
     */
    public function destroy(Resident $resident)
    {
        $resident->delete();

        return response()->json([
            'message' => 'Resident deleted successfully',
        ]);
    }
}
