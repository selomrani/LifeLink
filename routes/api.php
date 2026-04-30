<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BloodTypeController;
use App\Http\Controllers\Donations\StripeController;
use App\Http\Controllers\DonationsController;
use App\Http\Controllers\Feed\BloodRequestController;
use App\Http\Controllers\Feed\CommentController; // This is the correct Feed version
use App\Http\Controllers\Profile\UserProfileController;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Stripe\Stripe;

Route::get('/test', function () {
    return response()->json(['message' => 'LifeLink API is live!']);
});


Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);
Route::post('/forgot-password', ForgotPasswordController::class);
Route::post('/reset-password', ResetPasswordController::class);

Route::get('/bloodtypes', [BloodTypeController::class, 'index']);


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', LogoutController::class);


    Route::prefix('profile')->group(function () {
        Route::get('/', [UserProfileController::class, 'show']);
        Route::put('/', [UserProfileController::class, 'update']);
        Route::delete('/', [UserProfileController::class, 'destroy']);
    });


    Route::get('/feed', [BloodRequestController::class, 'index']);
    Route::post('/feed', [BloodRequestController::class, 'store']);
    Route::get('/feed/{bloodrequest}', [BloodRequestController::class, 'show']);
    Route::put('/feed/{id}', [BloodRequestController::class, 'update']);
    Route::delete('/feed/{id}', [BloodRequestController::class, 'destroy']);

    Route::post('/feed/{post}/comment', [CommentController::class, 'create']);
    Route::post('/feed/{bloodrequest}/donate', [StripeController::class, 'donate']);


    // donations

    Route::post('/post/{post}/donate', [DonationsController::class, 'offerDonation']);
    Route::put('/post/{post}/donations/accept', [DonationsController::class, 'acceptDonation']);
    Route::put('/post/{post}/donations/reject', [DonationsController::class, 'rejectDonation']);
    Route::get('/profile/donations', [DonationsController::class, 'myDonations']);
    Route::get('/donations/{donation}', [DonationsController::class, 'donationDetails']);
    Route::delete('/donations/{donation}', [DonationsController::class, 'deleteDonation']);

    // report
    Route::post('/users/{user}/report',[UserProfileController::class,'report']);

    // Admin actions

    Route::get('statistics',[AdminController::class,'statistics']);
    Route::get('users',[AdminController::class,'fetchUsers']);
    Route::get('reports',[AdminController::class,'fetchReports']);
    Route::put('users/{user}/ban',[AdminController::class,'ban']);
    Route::put('users/{user}/toggle-ban',[AdminController::class,'toggleBan']);
    Route::put('reports/{report}/review',[AdminController::class,'review']);
});

Route::get('/post/{post}/donations', [DonationsController::class, 'postDonationsIndex']);
