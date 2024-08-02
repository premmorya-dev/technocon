<?php

use App\Http\Controllers\Apps\PermissionManagementController;
use App\Http\Controllers\Apps\RoleManagementController;
use App\Http\Controllers\Apps\UserManagementController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Settings\SettingController;
use App\Http\Controllers\Event\EventController;
use App\Http\Controllers\DropDown\StudentSelectionController;
use App\Http\Controllers\DropDown\RegistrationStatusController;
use App\Http\Controllers\Settings\ProgramController;
use App\Http\Controllers\StudentRegistration\StudentRegistrationController;
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
// you can use verified middleware for verify email address.

// Route::get('/', function (){ return 'test'; });








Route::middleware(['auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('user-management.')->group(function () {
        Route::resource('/user-management/users', UserManagementController::class);
        Route::resource('/user-management/roles', RoleManagementController::class);
        Route::resource('/user-management/permissions', PermissionManagementController::class);
    });


    Route::name('user-management.')->group(function () {
        Route::resource('/user-management/users', UserManagementController::class);
        Route::resource('/user-management/roles', RoleManagementController::class);
        Route::resource('/user-management/permissions', PermissionManagementController::class);
    });
    
    Route::group(['prefix' => 'user'], function () {
        Route::get('list', [UserController::class, 'index'])->name('user.list');
        Route::get('add', [UserController::class, 'create'])->name('user.add');
        Route::post('store', [UserController::class, 'store'])->name('user.store');
        Route::post('destroy', [UserController::class, 'destroy'])->name('user.destroy');
        Route::get('edit/{user_id}', [UserController::class, 'edit'])->name('user.edit');
        Route::put('update/{user_id}', [UserController::class, 'update'])->name('user.update');
    });

   
    Route::get('settings/list', [SettingController::class, 'index'])->name('settings.list');
    Route::post('settings/create', [SettingController::class, 'store'])->name('settings.store');

    Route::group(['prefix' => 'event'], function () {
        Route::get('list', [EventController::class, 'index'])->name('event.list');
        Route::get('add', [EventController::class, 'create'])->name('event.add');
        Route::post('store', [EventController::class, 'store'])->name('event.store');
        Route::post('destroy', [EventController::class, 'destroy'])->name('event.destroy');
        Route::get('edit/{event_id}', [EventController::class, 'edit'])->name('event.edit');
        Route::put('update/{event_id}', [EventController::class, 'update'])->name('event.update');
    });



    Route::group(['prefix' => 'student-selection'], function () {
        Route::get('list', [StudentSelectionController::class, 'index'])->name('student_selection.list');
        Route::get('add', [StudentSelectionController::class, 'create'])->name('student_selection.add');
        Route::post('store', [StudentSelectionController::class, 'store'])->name('student_selection.store');
        Route::post('destroy', [StudentSelectionController::class, 'destroy'])->name('student_selection.destroy');
        Route::get('edit/{sd_selection_id}', [StudentSelectionController::class, 'edit'])->name('student_selection.edit');
        Route::put('update/{sd_selection_id}', [StudentSelectionController::class, 'update'])->name('student_selection.update');
    });


    Route::group(['prefix' => 'registration-status'], function () {
        Route::get('list', [RegistrationStatusController::class, 'index'])->name('registration_status.list');
        Route::get('add', [RegistrationStatusController::class, 'create'])->name('registration_status.add');
        Route::post('store', [RegistrationStatusController::class, 'store'])->name('registration_status.store');
        Route::post('destroy', [RegistrationStatusController::class, 'destroy'])->name('registration_status.destroy');
        Route::get('edit/{registration_status_id}', [RegistrationStatusController::class, 'edit'])->name('registration_status.edit');
        Route::put('update/{registration_status_id}', [RegistrationStatusController::class, 'update'])->name('registration_status.update');
    });


    Route::group(['prefix' => 'program'], function () {
        Route::get('list', [ProgramController::class, 'index'])->name('program.list');
        Route::get('add', [ProgramController::class, 'create'])->name('program.add');
        Route::post('store', [ProgramController::class, 'store'])->name('program.store');
        Route::post('destroy', [ProgramController::class, 'destroy'])->name('program.destroy');
        Route::get('edit/{program_id}', [ProgramController::class, 'edit'])->name('program.edit');
        Route::put('update/{program_id}', [ProgramController::class, 'update'])->name('program.update');
        Route::get('view', [ProgramController::class, 'view'])->name('program.view');
    });

    Route::group(['prefix' => 'student-registration'], function () {
        Route::get('list', [StudentRegistrationController::class, 'index'])->name('student_registration.list');
        Route::post('store', [StudentRegistrationController::class, 'store'])->name('student_registration.store');
        Route::post('destroy', [StudentRegistrationController::class, 'destroy'])->name('student_registration.destroy');
        Route::get('edit/{student_registration_id}', [StudentRegistrationController::class, 'edit'])->name('student_registration.edit');
        Route::put('update/{student_registration_id}', [StudentRegistrationController::class, 'update'])->name('student_registration.update');
        Route::get('view', [StudentRegistrationController::class, 'view'])->name('student_registration.view');
    });





 
});

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

require __DIR__ . '/auth.php';
require __DIR__ . '/frontend.php';
