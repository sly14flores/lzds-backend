<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use App\Notifications\EnrollmentNotification;
use Illuminate\Support\Facades\Notification;

use App\Models\Student;

Route::get('/preview/mail', function () {

    $student = Student::find(840);

    return (new EnrollmentNotification(['name'=>'Sly Flores']))
                ->toMail($student);

});

Route::get('/test/mail', function () {

    $student = Student::find(840);

    $student->notify(new EnrollmentNotification(['name'=>'Sly Flores']));

});
