<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string',
        ]);

        $loginValue = strtolower(trim((string) $validated['email']));

        $residentQuery = Resident::query();
        $residentQuery->whereRaw('LOWER(email) = ?', [$loginValue]);
        $resident = $residentQuery->first();

        if (!$resident) {
            return response()->json([
                'message' => 'Invalid resident credentials.'
            ], 401);
        }

        $query = User::query();
        $query->whereRaw('LOWER(email) = ?', [$loginValue]);

        $user = $query->first();
        if (!$user && $resident) {
            $residentEmail = strtolower(trim((string) ($resident->email ?? '')));

            if ($residentEmail !== '') {
                $user = User::whereRaw('LOWER(email) = ?', [$residentEmail])->first();
            }

            if ($user) {
                if (strtolower((string) ($user->role ?? '')) !== 'resident') {
                    $user->role = 'resident';
                    $user->save();
                }
            }

            if ($user) {
                // Reuse existing user account that matches resident email.
            } else {
            $baseUsername = $resident->email
                ? Str::before(strtolower((string) $resident->email), '@')
                : Str::slug((string) $resident->fullname, '');

            if ($baseUsername === '') {
                $baseUsername = 'resident';
            }

            $username = $baseUsername;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            $email = $resident->email;
            if (empty($email)) {
                return response()->json([
                    'message' => 'Resident record has no email. Please contact admin.'
                ], 422);
            }

            $user = User::create([
                'name' => $resident->fullname,
                'username' => $username,
                'fullname' => $resident->fullname,
                'email' => $email,
                'password' => Hash::make($validated['password']),
                'age' => $resident->age,
                'contact' => $resident->contact,
                'address' => $resident->address,
                'role' => 'resident',
            ]);
            }
        }

        if (!$user) {
            return response()->json([
                'message' => 'Invalid resident credentials.'
            ], 401);
        }

        $isResidentRole = strtolower((string) ($user->role ?? '')) === 'resident';
        if (!$isResidentRole) {
            return response()->json([
                'message' => 'Account is not registered as resident.'
            ], 403);
        }

        $passwordOk = Hash::check($validated['password'], $user->password);
        if (!$passwordOk && hash_equals((string) $user->password, (string) $validated['password'])) {
            $user->password = Hash::make($validated['password']);
            $user->save();
            $passwordOk = true;
        }

        if (!$passwordOk) {
            return response()->json([
                'message' => 'Invalid resident credentials.'
            ], 401);
        }

        return response()->json([
            'message' => 'Resident login successful',
            'user' => [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'fullname' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'age' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
        ]);

        $resident = DB::transaction(function () use ($validated) {
            User::create([
                'name' => $validated['fullname'],
                'username' => $validated['username'],
                'fullname' => $validated['fullname'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'age' => $validated['age'],
                'contact' => $validated['contact'] ?? null,
                'address' => $validated['address'],
                'role' => 'resident',
            ]);

            return Resident::create([
                'fullname' => $validated['fullname'],
                'email' => $validated['email'],
                'contact' => $validated['contact'] ?? null,
                'age' => $validated['age'],
                'address' => $validated['address'],
            ]);
        });

        return response()->json([
            'message' => 'Registered successfully',
            'resident' => $resident,
        ], 201);
    }
}