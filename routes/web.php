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
Route::get('/', 'DashboardController@index')->name('dashboard');

/*
 * Student routes
 */
Route::get('/courses', 'CourseController@index')->name('courses.index');

Route::post('/courses/{courseId}/enroll', 'CourseEnrollmentController@store')->name('enrollments.store');
Route::delete('/courses/{courseId}/unenroll', 'CourseEnrollmentController@destroy')->name('enrollments.destroy');

Route::get('/enrollments/{enrollmentId}/exchange', 'EnrollmentExchangeController@create')->name('exchanges.create');
Route::post('/enrollments/{enrollmentId}/exchange', 'EnrollmentExchangeController@store')->name('exchanges.store');

Route::post('/exchanges/{id}/confirm', 'ExchangeController@confirm')->name('exchanges.confirm');
Route::post('/exchanges/{id}/decline', 'ExchangeController@decline')->name('exchanges.decline');
Route::delete('/exchanges/{id}', 'ExchangeController@destroy')->name('exchanges.destroy');

Route::get('/registrations/confirm/{token}', 'Auth\AccountVerificationController@store')->name('register.confirm');
Route::post('/registrations/email', 'Auth\AccountVerificationController@sendEmail')->name('register.resend_confirmation');

Route::get('/groups', 'GroupController@index')->name('groups.index');
Route::get('/groups/{id}', 'GroupController@show')->name('groups.show');
Route::get('/groups/{id}/store', 'GroupController@store')->name('groups.store');
Route::get('/groups/{id}/destroy', 'GroupController@destroy')->name('groups.destroy');

Route::get('/groups/invitations/{id}/accept', 'GroupController@update')->name('groups.update');

Route::get('/groups/{id}/invitations', 'InvitationController@index')->name('invitations.index');
Route::post('/groups/{groupId}/invitations/{courseId}/store', 'InvitationController@store')->name('invitations.store');
Route::get('/groups/invitations/{id}/destroy', 'InvitationController@destroy')->name('invitations.destroy');

/*
 * Admin routes
 */
Route::get('/courses/{id}/students', 'Admin\CourseController@show')->name('students.index');

Route::get('/enrollments/export', 'Admin\EnrollmentController@export')->name('enrollments.export');
Route::get('/enrollments/import', 'Admin\EnrollmentController@import')->name('enrollments.import');
Route::post('/enrollments/import', 'Admin\EnrollmentController@storeImport')->name('enrollments.storeImport');

Route::get('/exchanges', 'Admin\ExchangeController@index')->name('exchanges.index');

Route::get('/settings/edit', 'Admin\SettingsController@edit')->name('settings.edit');
Route::put('/settings', 'Admin\SettingsController@update')->name('settings.update');

Route::get('/admin/groups', 'Admin\GroupController@index')->name('admin.groups.index');
Route::get('/admin/{id}/groups', 'Admin\GroupController@show')->name('admin.groups.show');

Route::post('/course/{id}', 'Admin\CourseController@update')->name('course.update');