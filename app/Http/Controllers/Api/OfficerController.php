<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangayOfficer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OfficerController extends Controller
{
    public function index()
    {
        $officers = BarangayOfficer::query()
            ->latest('id')
            ->get(['id', 'fullname', 'username', 'email', 'contact', 'address', 'role', 'created_at']);

        return response()->json([
            'data' => $officers,
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:barangay_officers,email',
            'username' => 'required|string|max:255|unique:barangay_officers,username',
            'password' => 'required|string|min:6|confirmed',
            'contact' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role' => 'nullable|in:admin,official',
        ]);

        $officer = BarangayOfficer::create([
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'contact' => $validated['contact'] ?? null,
            'address' => $validated['address'] ?? null,
            'role' => $validated['role'] ?? 'admin',
        ]);

        return response()->json([
            'message' => 'Officer registered successfully',
            'officer' => $officer
        ], 201);
    }
}
