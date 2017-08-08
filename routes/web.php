<?php

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

Auth::routes();

// Student-only routes
Route::middleware(['auth', 'can.student'])->group(function () {
    Route::get('/', 'DashboardController@index')->name('home');
    Route::get('/confirm/{token}', 'Auth\RegisterController@confirm')
         ->name('register.confirm');
    Route::post('/registrations/resend-confirmation', 'Auth\RegisterController@resendConfirmationEmail')
         ->name('register.resend_confirmation');

    // Allowed routes after confirmation
    Route::middleware('confirmed')->group(function () {
        Route::get('/courses', 'CourseController@index')->name('courses.index');
        Route::post('/enrollments', 'EnrollmentController@store')->name('enrollments.create');
        Route::post('/exchanges', 'ExchangeController@store')->name('exchanges.create');
        Route::post('/exchanges/confirm', 'ExchangeController@storeConfirmation')->name('exchanges.confirm');
    });
});

// Admin-only routes
Route::middleware(['auth', 'can.admin'])->namespace('Admin')->group(function () {
    Route::get('/enrollments/export', 'EnrollmentController@export')->name('enrollments.export');
    Route::match(['PUT', 'PATCH'], 'settings', 'SettingsController@update')->name('settings.update');
});
