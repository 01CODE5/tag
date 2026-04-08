<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\OfficerController;

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

Route::get('/', function () {
    return view('website.website');
});
Route::get('/home', function () {
    return view('website.home');
});
Route::get('/dashboard', function () {
    return view('website.dashadmin');
});
Route::get('/certificate', function () {
    return view('website.cert');
});
Route::get('/resident', function () {
    return view('website.resident');
});
Route::get('/settings', function () {
    return view('website.settings');
});
Route::get('/docs', function () {
    return view('website.docs');
});
Route::get('/loginadmin', function () {
    return view('website.loginadmin');
})->name('login');

Route::get('/dash', function () {
    return view('website.dashadmin');
});

Route::get('/barangay', function () {
    return view('website.barangay');
});

Route::post('/barangay/barangay', [OfficerController::class, 'register'])
->name('barangay.register');
