<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StudentApprovalController;

// Admin Student Approval Routes
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/students', [StudentApprovalController::class, 'index'])
            ->name('students.index');

        Route::patch('/students/{student}/approve', [StudentApprovalController::class, 'approve'])
            ->name('students.approve');

        Route::patch('/students/{student}/reject', [StudentApprovalController::class, 'reject'])
            ->name('students.reject');
    });

Route::get('/', function () {
    return view('welcome');
});

// Redirect users to their correct EduTrack dashboard
Route::get('/dashboard', function () {

    $user = \Illuminate\Support\Facades\Auth::user();

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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// EduTrack Admin Dashboard
Route::middleware(['auth', 'role:admin'])
    ->get('/admin/dashboard', function () {
        return view('dashboards.admin');
    })
    ->name('admin.dashboard');

// EduTrack Lecturer Dashboard
Route::middleware(['auth', 'role:lecturer'])
    ->get('/lecturer/dashboard', function () {
        return view('dashboards.lecturer');
    })
    ->name('lecturer.dashboard');

    // EduTrack Student Dashboard
Route::middleware(['auth', 'role:student'])
    ->get('/student/dashboard', function () {
        return view('dashboards.student');
    })
    ->name('student.dashboard');

require __DIR__.'/auth.php';
