<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\RequestEmailMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

class AdminController extends Controller
{
    /**
     * List admin / official accounts
     */
  public function store(Request $request)
{
    $me = $request->user();
    $allowed = ['superadmin','super_admin','highadmin','root'];
    if (!$me || !in_array(strtolower($me->role ?? ''), $allowed)) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $validated = $request->validate([
        'username' => 'required|string|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'fullname' => 'required|string',
        'contact' => 'nullable|string',
        'address' => 'nullable|string',
        'role' => 'required|in:admin,barangay,official',
    ]);

    $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
    $validated['name'] = $validated['fullname'];
    $validated['age'] = null;

    $user = \App\Models\User::create($validated);

    return response()->json(['message' => 'Created', 'user' => $user], 201);
}

    public function sendRequestEmail(Request $request)
    {
        if (session('admin_logged_in') !== true || session('admin_role') !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'recipient' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'ref' => 'nullable|string|max:100',
            'name' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:255',
            'reason' => 'nullable|string|max:255',
            'date' => 'nullable|string|max:100',
        ]);

        try {
            Mail::to($validated['recipient'])->send(new RequestEmailMail($validated));

            return response()->json([
                'message' => 'Email sent successfully.',
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Unable to send email right now.',
            ], 500);
        }
    }

    /**
     * Delete an admin user
     */
    public function destroy(Request $request, $id)
    {
        $me = $request->user();
        $allowed = ['superadmin','super_admin','highadmin','root'];
        if (!$me || !in_array(strtolower($me->role ?? ''), $allowed)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'Not found'], 404);

        // Prevent deleting other superadmins
        $superRoles = ['superadmin','super_admin','highadmin','root'];
        if (in_array(strtolower($user->role ?? ''), $superRoles)) {
            return response()->json(['message' => 'Cannot delete a super-admin'], 403);
        }

        $user->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
