<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\Api\AddressesController;
use App\Http\Controllers\Api\SelectionsController;

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\DefaultPasswords;
use App\Http\Controllers\Api\Students\PortalController;

use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\SendParentalConsent;

use App\Http\Controllers\Api\ResetPasswordController;

/**
 * Addresses
 */
Route::prefix('address')->group(function() {

    Route::get('regions', [AddressesController::class, 'regions']);
    Route::get('provinces/{code}', [AddressesController::class, 'provinces']);
    Route::get('cities/{code}', [AddressesController::class, 'cities']);
    Route::get('barangays/{code}', [AddressesController::class, 'barangays']);

});

/**
 * Selections
 */
Route::prefix('selections')->group(function() {

    Route::get('dialects', [SelectionsController::class, 'dialects']);  
    Route::get('indigenous/groups', [SelectionsController::class, 'indigenousGroups']);  
    Route::get('levels', [SelectionsController::class, 'levels']);  
    Route::get('fees/{level_id}', [SelectionsController::class, 'feesByLevel']);  
    Route::get('questionnaires', [SelectionsController::class, 'questionnaires']);  

});

/**
 * Students
 */
Route::apiResources([
    'students' => StudentController::class,
],[
    'only' => ['index']
]);
Route::apiResources([
    'student' => StudentController::class,
],[
    'except' => ['index']
]);
Route::post('profile/student/online', [StudentController::class, 'profileOnline']);
Route::post('query/student/online', [StudentController::class, 'queryByLRNBday']);

/**
 * Enrollment
 */
Route::apiResources([
    'enrollments' => EnrollmentController::class,
],[
    'only' => ['index']
]);
Route::apiResources([
    'enrollment' => EnrollmentController::class,
],[
    'except' => ['index']
]);
Route::post('enroll/student/online', [EnrollmentController::class, 'enrollOnline']);
Route::get('payment/info/{uuid}', [EnrollmentController::class, 'paymentInfo']);
Route::put('payment/gcash/{uuid}', [EnrollmentController::class, 'updateGcash']);
Route::put('payment/paypal/{uuid}', [EnrollmentController::class, 'updatePaypal']);
Route::get('school/current_sy', [EnrollmentController::class, 'currentSchoolYear']);

/**
 * Logins
 */
Route::prefix('login')->group(function() {

    Route::post('student', [LoginController::class, 'student']);
    Route::post('staff', [LoginController::class, 'staff']);
    
});

/**
 * Logout
 */
Route::post('logout', [LoginController::class, 'logout']);

/**
 * Default Passwords
 */
Route::prefix('update/password')->group(function() {
    
    Route::put('student', [DefaultPasswords::class, 'student']);
    
});

/**
 * Forgot Password
 */
Route::prefix('password')->group(function() {

    Route::post('email', [ResetPasswordController::class, 'sendResetLinkEmail']);
    Route::post('reset', [ResetPasswordController::class, 'resetPassword']);

});

/**
 * Students Portal
 */
Route::prefix('portal')->group(function() {

    Route::prefix('student')->group(function() {

        Route::get('profile', [PortalController::class, 'profile']);
        Route::get('enrollments', [PortalController::class, 'enrollments']);
        Route::get('enrollment/{id}', [PortalController::class, 'enrollment']);

    });

});

/**
 * Send Email
 */
Route::post('send/email', [EmailController::class, 'send']);

/**
 * Send Parental Consent
 */
Route::post('send/parental-consent', SendParentalConsent::class);