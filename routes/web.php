<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\StudentApprovalController;
use App\Http\Controllers\Admin\CourseApprovalController;
use App\Http\Controllers\Lecturer\CourseController;
use App\Http\Controllers\Student\EnrollmentController;

// Welcome Page
Route::get('/', function () {
    return view('welcome');
});

// Redirect users to their correct EduTrack dashboard
Route::get('/dashboard', function () {

    $user = Auth::user();

    if ($user->account_status !== 'approved') {
        abort(403, 'Your account is not approved.');
    }

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'lecturer' => redirect()->route('lecturer.dashboard'),
        'student' => redirect()->route('student.dashboard'),
        default => abort(403),
    };

})->middleware(['auth'])->name('dashboard');

// Profile Routes
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// ADMIN ROUTES

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Admin Dashboard
        Route::get('/dashboard', function () {
            return view('dashboards.admin');
        })->name('dashboard');

        // Student Approval
        Route::get('/students', [StudentApprovalController::class, 'index'])
            ->name('students.index');

        Route::patch('/students/{student}/approve', [StudentApprovalController::class, 'approve'])
            ->name('students.approve');

        Route::patch('/students/{student}/reject', [StudentApprovalController::class, 'reject'])
            ->name('students.reject');

        // Course Approval
        Route::get('/courses', [CourseApprovalController::class, 'index'])
            ->name('courses.index');

        Route::patch('/courses/{course}/approve', [CourseApprovalController::class, 'approve'])
            ->name('courses.approve');

        Route::patch('/courses/{course}/reject', [CourseApprovalController::class, 'reject'])
            ->name('courses.reject');
    });

// LECTURER ROUTES

Route::middleware(['auth', 'role:lecturer'])
    ->prefix('lecturer')
    ->name('lecturer.')
    ->group(function () {

        // Lecturer Dashboard
        Route::get('/dashboard', function () {
            return view('dashboards.lecturer');
        })->name('dashboard');

        // Course Management
        Route::get('/courses', [CourseController::class, 'index'])
            ->name('courses.index');

        Route::get('/courses/create', [CourseController::class, 'create'])
            ->name('courses.create');

        Route::post('/courses', [CourseController::class, 'store'])
            ->name('courses.store');
    });

// STUDENT ROUTES

Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        // Student Dashboard
        Route::get('/dashboard', function () {
            return view('dashboards.student');
        })->name('dashboard');

        // Display approved courses
        Route::get('/courses', [EnrollmentController::class, 'index'])
            ->name('courses.index');

        // Request enrollment in a course
        Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])
            ->name('courses.enroll');
    });

// Laravel Authentication Routes
require __DIR__.'/auth.php';
