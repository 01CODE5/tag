<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangayOfficer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OfficerController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'password' => 'required|string',
        ]);

        $loginRaw = $validated['login'] ?? $validated['email'] ?? '';
        $loginValue = strtolower(trim((string) $loginRaw));
        if ($loginValue === '') {
            return response()->json([
                'message' => 'Please enter username or email.'
            ], 422);
        }

        $officer = $this->resolveOfficerByLogin($loginValue);
        if (!$officer) {
            return response()->json([
                'message' => 'Invalid credentials or account not found in the database.'
            ], 401);
        }

        $passwordOk = $this->verifyOfficerPassword($officer, $validated['password']);

        if (!$passwordOk) {
            return response()->json([
                'message' => 'Invalid credentials or account not found in the database.'
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $officer->id,
                'fullname' => $officer->fullname,
                'email' => $officer->email,
                'username' => $officer->username,
                'role' => $officer->role,
            ],
        ]);
    }

    public function loginAdmin(Request $request)
    {
        $validated = $request->validate([
            'login' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'password' => 'required|string',
        ]);

        $loginRaw = $validated['login'] ?? $validated['email'] ?? '';
        $loginValue = strtolower(trim((string) $loginRaw));
        if ($loginValue === '') {
            return response()->json([
                'message' => 'Please enter username or email.'
            ], 422);
        }

        $officer = $this->resolveOfficerByLogin($loginValue);
        if (!$officer) {
            return response()->json([
                'message' => 'Invalid credentials or account not found in the database.'
            ], 401);
        }

        if (strtolower((string) $officer->role) !== 'admin') {
            return response()->json([
                'message' => 'Access denied. Admin accounts only.'
            ], 403);
        }

        $passwordOk = $this->verifyOfficerPassword($officer, $validated['password']);
        if (!$passwordOk) {
            return response()->json([
                'message' => 'Invalid credentials or account not found in the database.'
            ], 401);
        }

        $request->session()->regenerate();
        $request->session()->put('admin_logged_in', true);
        $request->session()->put('admin_role', 'admin');
        $request->session()->put('admin_id', $officer->id);
        $request->session()->put('admin_name', $officer->fullname);

        return response()->json([
            'message' => 'Admin login successful',
            'user' => [
                'id' => $officer->id,
                'fullname' => $officer->fullname,
                'email' => $officer->email,
                'username' => $officer->username,
                'role' => $officer->role,
            ],
        ]);
    }

    private function resolveOfficerByLogin(string $loginValue): ?BarangayOfficer
    {
        $isEmailLogin = filter_var($loginValue, FILTER_VALIDATE_EMAIL) !== false;

        if ($isEmailLogin) {
            $officer = BarangayOfficer::whereRaw('LOWER(email) = ?', [$loginValue])->first();
            if ($officer) {
                return $officer;
            }
        }

        return BarangayOfficer::whereRaw('LOWER(username) = ?', [$loginValue])->first();
    }

    private function verifyOfficerPassword(BarangayOfficer $officer, string $password): bool
    {
        $passwordOk = Hash::check($password, $officer->password);

        // Backward compatibility for old records that may still store plain text.
        if (!$passwordOk && hash_equals((string) $officer->password, (string) $password)) {
            $officer->password = Hash::make($password);
            $officer->save();
            $passwordOk = true;
        }

        return $passwordOk;
    }

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
