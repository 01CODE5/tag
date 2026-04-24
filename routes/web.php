<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\OfficerController;
use App\Http\Controllers\Website\WebsiteController;

$adminViewResponse = function (string $view) {
    if (session('admin_logged_in') !== true || session('admin_role') !== 'admin') {
        return redirect('/loginadmin');
    }

    return response()
        ->view($view)
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
};

Route::get('login', function () {
    return view('website.login');
});
Route::get('register', function () {
    return view('website.register');
});
Route::get('/users', function () {
    return view('website.users');
});
Route::post('resident/register', [AuthController::class, 'register'])->name('register');
// Forgot password email sender
Route::post('/resident/forgot-password', [WebsiteController::class, 'sendResetEmail']);
Route::post('/resident/change-password', [WebsiteController::class, 'changeResidentPassword']);
Route::post('/rest-acc/change-password', [WebsiteController::class, 'changePasswordByEmail']);


Route::get('/', function () {
    return view('website.Website');
});
Route::get('/home', function () {
    return view('website.home');
});
Route::get('/dashs', function () use ($adminViewResponse) {
    return $adminViewResponse('website.dash');
});
Route::post('/dashs/send-email', [AdminController::class, 'sendRequestEmail']);
Route::get('/dashboard', function () use ($adminViewResponse) {
    return $adminViewResponse('website.dashadmin');
});
Route::get('/certificate', function () use ($adminViewResponse) {
    return $adminViewResponse('website.cert');
});
Route::get('/cert', function () {
    return redirect('/certificate');
});
Route::get('/resident', function () use ($adminViewResponse) {
    return $adminViewResponse('website.resident');
});
Route::get('/rest-acc', function () use ($adminViewResponse) {
    return $adminViewResponse('website.rest-acc');
});
Route::post('/rest-acc/send-email', [AdminController::class, 'sendRequestEmail']);
Route::get('/docs', function () {
    return view('website.docs');
});
Route::post('/docs/certificate-pdf', [WebsiteController::class, 'downloadCertificatePdf']);
Route::get('/loginadmin', function () {
    return view('website.loginadmin');
})->name('loginadmin');
Route::post('/loginadmin', [OfficerController::class, 'loginAdmin'])->name('loginadmin.submit');
Route::post('/loginadmin/logout', function (Request $request) {
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json([
        'message' => 'Logged out successfully.'
    ])
    ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
    ->header('Pragma', 'no-cache')
    ->header('Expires', '0');
})->name('loginadmin.logout');

Route::get('/dash', function () use ($adminViewResponse) {
    return $adminViewResponse('website.dashadmin');
});

Route::get('/barangay', function () use ($adminViewResponse) {
    return $adminViewResponse('website.barangay');
});

Route::post('/barangay/barangay', [OfficerController::class, 'register'])
->name('barangay.register');

Route::post('/officers/{id}/request-password-change', [OfficerController::class, 'requestPasswordChange']);
Route::post('/password-change-requests/{id}/approve', [OfficerController::class, 'approvePasswordChangeRequest']);
Route::post('/password-change-requests/{id}/reject', [OfficerController::class, 'rejectPasswordChangeRequest']);

Route::post('/resident/login', [AuthController::class, 'login'])->name('resident.login');

Route::post('/officers/{id}/request-password-change', [OfficerController::class, 'requestPasswordChange']);
Route::post('/password-change-requests/{id}/approve', [OfficerController::class, 'approvePasswordChangeRequest']);
Route::post('/password-change-requests/{id}/reject', [OfficerController::class, 'rejectPasswordChangeRequest']);