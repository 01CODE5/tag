<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\RequestEmailMail;
use App\Http\Controllers\Website\WebsiteController;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

class AdminController extends Controller
{
    private function validateMailConfiguration(): ?string
    {
        $defaultMailer = config('mail.default');

        if (in_array($defaultMailer, ['log', 'array'], true)) {
            return null;
        }

        if ($defaultMailer !== 'smtp') {
            return 'Mail is not configured for SMTP. Update MAIL_MAILER and SMTP values in your .env file.';
        }

        $required = [
            'mail.mailers.smtp.host' => 'MAIL_HOST',
            'mail.mailers.smtp.port' => 'MAIL_PORT',
            'mail.mailers.smtp.username' => 'MAIL_USERNAME',
            'mail.mailers.smtp.password' => 'MAIL_PASSWORD',
            'mail.from.address' => 'MAIL_FROM_ADDRESS',
        ];

        foreach ($required as $configKey => $envName) {
            $value = config($configKey);
            if ($value === null || $value === '') {
                return 'Missing mail configuration: ' . $envName;
            }
        }

        $username = strtolower((string) config('mail.mailers.smtp.username', ''));
        $password = strtolower((string) config('mail.mailers.smtp.password', ''));
        $fromAddress = strtolower((string) config('mail.from.address', ''));

        if (str_contains($password, 'your_16_char_app_password') || str_contains($username, 'your_gmail@gmail.com')) {
            return 'Please set real Gmail SMTP credentials in .env (MAIL_USERNAME and MAIL_PASSWORD).';
        }

        if ($fromAddress !== '' && filter_var($fromAddress, FILTER_VALIDATE_EMAIL) === false) {
            return 'MAIL_FROM_ADDRESS is not a valid email address.';
        }

        return null;
    }

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

        $mailConfigError = $this->validateMailConfiguration();
        if ($mailConfigError) {
            return response()->json(['message' => $mailConfigError], 422);
        }

        $validated = $request->validate([
            'recipient' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'ref' => 'nullable|string|max:100',
            'name' => 'nullable|string|max:255',
            'age' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'purpose' => 'nullable|string|max:255',
            'reason' => 'nullable|string|max:255',
            'date' => 'nullable|string|max:100',
            'template' => 'nullable|array',
            'pdfAttachmentBase64' => 'nullable|string',
        ]);

        try {
            $filename = 'certificate-' . Str::slug($validated['ref'] ?? uniqid()) . '.pdf';
            $mail = new RequestEmailMail($validated);
            $attached = false;
            $tmpPath = null;

            // Prefer browser-generated PDF (live preview layout)
            if (!empty($validated['pdfAttachmentBase64'])) {
                $pdfData = preg_replace('#^data:application/[^;]+;base64,#', '', $validated['pdfAttachmentBase64']);
                $pdfBytes = base64_decode($pdfData, true);
                if ($pdfBytes !== false) {
                    $mail->attachData($pdfBytes, $filename, [
                        'mime' => 'application/pdf',
                    ]);
                    $attached = true;
                }
            }

            // Fallback: generate PDF using server-side logic
            if (! $attached) {
                $pdfData = [
                    'ref' => $validated['ref'] ?? '',
                    'name' => $validated['name'] ?? '',
                    'age' => $request->input('age', ''),
                    'address' => $request->input('address', ''),
                    'purpose' => $validated['purpose'] ?? '',
                    'date' => $validated['date'] ?? '',
                    'template' => $request->input('template', []),
                ];
                $websiteController = new WebsiteController();
                $pdfContent = $websiteController->buildCertificatePdf($pdfData);
                $tmpPath = storage_path('app/' . $filename);
                file_put_contents($tmpPath, $pdfContent);

                $mail->attach($tmpPath, [
                    'as' => $filename,
                    'mime' => 'application/pdf',
                ]);
            }

            Mail::to($validated['recipient'])->send($mail);

            // Clean up temp file
            if ($tmpPath && file_exists($tmpPath)) {
                @unlink($tmpPath);
            }

            return response()->json([
                'message' => 'Email sent successfully with PDF attached.',
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => app()->hasDebugModeEnabled()
                    ? ('Unable to send email: ' . $e->getMessage())
                    : 'Unable to send email right now.',
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
