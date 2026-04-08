<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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

        $resident = Resident::create([
                'user_id' => 1,
                'fullname' => $validated['fullname'],
                'email' => $validated['email'],
                'contact' => $validated['contact'] ?? null,
                'age' => $validated['age'],
                'address' => $validated['address'],
            ]);  

        // $user = DB::transaction(function () use ($validated) {
        //     $user = User::create([
        //         'name' => $validated['fullname'],
        //         'username' => $validated['username'],
        //         'email' => $validated['email'],
        //         'password' => Hash::make($validated['password']),
        //         'role' => 'resident',
        //     ]);

        //     Resident::create([
        //         'user_id' => $user->id,
        //         'fullname' => $validated['fullname'],
        //         'email' => $validated['email'],
        //         'contact' => $validated['contact'] ?? null,
        //         'age' => $validated['age'],
        //         'address' => $validated['address'],
        //     ]);

        //     return $user;
        // });

        // $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registered successfully',
            // 'token' => $token,
            'resident' => $resident,
        ], 201);
    }
}