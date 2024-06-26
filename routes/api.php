<?php

use App\Presentation\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/register-user', [AuthController::class, 'register'])->name('auth.register');
Route::post('/email/verify-register/', [AuthController::class, 'verifyOTPAfterRegister'])->name('auth.verifyOTPAfterRegister');
Route::post('/email/verify-login/', [AuthController::class, 'verifyOTPAfterLogin'])->name('auth.verifyOTPAfterLogin');
Route::get('/email/verification-notification/{userId}', [AuthController::class, 'resendVerificationNotification'])->middleware(['throttle:6,1'])->name('auth.resendVerificationNotification');
Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->middleware(['throttle:6,1', 'guest'])->name('auth.forgotPassword');
Route::post('/forgot-password/verify-otp', [AuthController::class, 'verifyOTPForgotPassword'])->middleware('guest')->name('auth.verifyOTPForgotPassword');
Route::get('/forgot-password/resend-otp/{userId}', [AuthController::class, 'resendOTPForgotPassword'])->middleware(['throttle:6,1'])->name('auth.resendOTPForgotPassword');
Route::post('/forgot-password/set-new-password', [AuthController::class, 'setNewPasswordAfterForgot'])->middleware('guest')->name('auth.setNewPasswordAfterForgot');

Route::group(['middleware' => ['auth:api', 'verified']], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('auth.logout');
    Route::post('/check-login', function (Request $request) {
        return $request->user();
    });
});
