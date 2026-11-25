<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\StudentApprovalController;
use App\Http\Controllers\Admin\CourseApprovalController;
use App\Http\Controllers\Admin\EnrollmentApprovalController;
use App\Http\Controllers\Admin\EnrollmentRemovalController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ArchiveController as AdminArchiveController;
use App\Http\Controllers\Lecturer\CourseController;
use App\Http\Controllers\Lecturer\CourseManagementController;
use App\Http\Controllers\Lecturer\MaterialController;
use App\Http\Controllers\Lecturer\MaterialManagementController;
use App\Http\Controllers\Lecturer\AssignmentController;
use App\Http\Controllers\Lecturer\AssignmentManagementController;
use App\Http\Controllers\Lecturer\SubmissionController as LecturerSubmissionController;
use App\Http\Controllers\Lecturer\ProgressController;
use App\Http\Controllers\Lecturer\ArchiveController as LecturerArchiveController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\EnrollmentController;
use App\Http\Controllers\Student\AssignmentController as StudentAssignmentController;
use App\Http\Controllers\Student\SubmissionController;
use App\Http\Controllers\Student\SubmissionManagementController;
use App\Http\Controllers\Student\ProgressController as StudentProgressController;

// Welcome Page
Route::get('/', function () {
    return view('welcome');
});

// Dashboard Redirection
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
        Route::get('/dashboard', [DashboardController::class, 'admin'])
            ->name('dashboard');

        // Academic Reports
        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/reports/export', [ReportController::class, 'export'])
            ->name('reports.export');

        // Student Approval
        Route::get('/students', [StudentApprovalController::class, 'index'])
            ->name('students.index');

        Route::patch('/students/{student}/approve', [StudentApprovalController::class, 'approve'])
            ->name('students.approve');

        Route::patch('/students/{student}/reject', [StudentApprovalController::class, 'reject'])
            ->name('students.reject');

        // User Management
        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [AdminUserController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [AdminUserController::class, 'store'])
            ->name('users.store');

        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])
            ->whereNumber('user')
            ->name('users.edit');

        Route::patch('/users/{user}', [AdminUserController::class, 'update'])
            ->whereNumber('user')
            ->name('users.update');

        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])
            ->whereNumber('user')
            ->name('users.destroy');

        // Course Management
        Route::get('/courses', [CourseApprovalController::class, 'index'])
            ->name('courses.index');

        Route::patch('/courses/{course}/approve', [CourseApprovalController::class, 'approve'])
            ->name('courses.approve');

        Route::patch('/courses/{course}/reject', [CourseApprovalController::class, 'reject'])
            ->name('courses.reject');

        // Enrolment Management
        Route::get('/enrollments', [EnrollmentApprovalController::class, 'index'])
            ->name('enrollments.index');

        Route::patch('/enrollments/{enrollment}/approve', [EnrollmentApprovalController::class, 'approve'])
            ->middleware('active.course')
            ->name('enrollments.approve');

        Route::patch('/enrollments/{enrollment}/reject', [EnrollmentApprovalController::class, 'reject'])
            ->middleware('active.course')
            ->name('enrollments.reject');

        Route::delete('/enrollments/{enrollment}', [EnrollmentRemovalController::class, 'destroy'])
            ->whereNumber('enrollment')
            ->middleware('active.course')
            ->name('enrollments.destroy');

        // Course Archive Management
        Route::get('/archive', [AdminArchiveController::class, 'index'])
            ->name('archive.index');

        Route::patch('/courses/{course}/archive', [AdminArchiveController::class, 'archive'])
            ->name('archive.store');

        Route::patch('/courses/{course}/restore', [AdminArchiveController::class, 'restore'])
            ->name('archive.restore');

    });

// LECTURER ROUTES
Route::middleware(['auth', 'role:lecturer'])
    ->prefix('lecturer')
    ->name('lecturer.')
    ->group(function () {

        // Lecturer Dashboard
        Route::get('/dashboard', [DashboardController::class, 'lecturer'])
            ->name('dashboard');

        // Academic Reports
        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/reports/export', [ReportController::class, 'export'])
            ->name('reports.export');

        // Feature Course Selection
        Route::get('/select-course/{feature}', [CourseController::class, 'selectFeature'])
            ->whereIn('feature', ['materials', 'assignments', 'progress'])
            ->name('courses.select-feature');

        // Course Management
        Route::get('/courses', [CourseController::class, 'index'])
            ->name('courses.index');

        Route::get('/courses/create', [CourseController::class, 'create'])
            ->name('courses.create');

        Route::get('/courses/{course}', [CourseController::class, 'show'])
            ->whereNumber('course')
            ->name('courses.show');

        Route::post('/courses', [CourseController::class, 'store'])
            ->name('courses.store');

        // Course Editing
        Route::get('/courses/{course}/edit', [CourseManagementController::class, 'edit'])
            ->whereNumber('course')
            ->middleware('active.course')
            ->name('courses.edit');

        Route::patch('/courses/{course}', [CourseManagementController::class, 'update'])
            ->whereNumber('course')
            ->middleware('active.course')
            ->name('courses.update');

        // Learning Materials
        Route::get('/courses/{course}/materials', [MaterialController::class, 'index'])
            ->name('materials.index');

        Route::get('/courses/{course}/materials/create', [MaterialController::class, 'create'])
            ->middleware('active.course')
            ->name('materials.create');

        Route::post('/courses/{course}/materials', [MaterialController::class, 'store'])
            ->middleware('active.course')
            ->name('materials.store');

        Route::get('/materials/{material}/edit', [MaterialManagementController::class, 'edit'])
            ->whereNumber('material')
            ->middleware('active.course')
            ->name('materials.edit');

        Route::patch('/materials/{material}', [MaterialManagementController::class, 'update'])
            ->whereNumber('material')
            ->middleware('active.course')
            ->name('materials.update');

        Route::delete('/materials/{material}', [MaterialManagementController::class, 'destroy'])
            ->whereNumber('material')
            ->middleware('active.course')
            ->name('materials.destroy');

        // Assignment Management
        Route::get('/courses/{course}/assignments', [AssignmentController::class, 'index'])
            ->name('assignments.index');

        Route::get('/courses/{course}/assignments/create', [AssignmentController::class, 'create'])
            ->middleware('active.course')
            ->name('assignments.create');

        Route::post('/courses/{course}/assignments', [AssignmentController::class, 'store'])
            ->middleware('active.course')
            ->name('assignments.store');

        Route::get('/assignments/{assignment}/edit', [AssignmentManagementController::class, 'edit'])
            ->whereNumber('assignment')
            ->middleware('active.course')
            ->name('assignments.edit');

        Route::patch('/assignments/{assignment}', [AssignmentManagementController::class, 'update'])
            ->whereNumber('assignment')
            ->middleware('active.course')
            ->name('assignments.update');

        Route::delete('/assignments/{assignment}', [AssignmentManagementController::class, 'destroy'])
            ->whereNumber('assignment')
            ->middleware('active.course')
            ->name('assignments.destroy');

        // Student Submissions
        Route::get('/assignments/{assignment}/submissions', [LecturerSubmissionController::class, 'index'])
            ->name('submissions.index');

        Route::get('/submissions/{submission}/download', [LecturerSubmissionController::class, 'download'])
            ->name('submissions.download');

        Route::patch('/submissions/{submission}/grade', [LecturerSubmissionController::class, 'grade'])
            ->middleware('active.course')
            ->name('submissions.grade');

        // Student Progress
        Route::get('/courses/{course}/progress', [ProgressController::class, 'index'])
            ->name('progress.index');

        // Archived Courses
        Route::get('/archive', [LecturerArchiveController::class, 'index'])
            ->name('archive.index');

        Route::get('/archive/{course}', [LecturerArchiveController::class, 'show'])
            ->whereNumber('course')
            ->name('archive.show');

    });

// STUDENT ROUTES
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        // Student Dashboard
        Route::get('/dashboard', [DashboardController::class, 'student'])
            ->name('dashboard');

        // Academic Progress
        Route::get('/my-progress', [StudentProgressController::class, 'index'])
            ->name('progress.index');

        // Assignment Overview
        Route::get('/my-assignments', [StudentAssignmentController::class, 'overview'])
            ->name('assignments.overview');

        // Browse Courses
        Route::get('/courses', [EnrollmentController::class, 'index'])
            ->name('courses.index');

        // Request Enrolment
        Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])
            ->middleware('active.course')
            ->name('courses.enroll');

        // My Courses
        Route::get('/my-classes', [EnrollmentController::class, 'myClasses'])
            ->name('classes.index');

        // Course Workspace
        Route::get('/courses/{course}', [StudentCourseController::class, 'show'])
            ->whereNumber('course')
            ->name('courses.show');

        // Learning Materials
        Route::get('/courses/{course}/materials', [EnrollmentController::class, 'materials'])
            ->name('materials.index');

        // View Assignments
        Route::get('/courses/{course}/assignments', [StudentAssignmentController::class, 'index'])
            ->name('assignments.index');

        // Submit Assignments
        Route::post('/assignments/{assignment}/submit', [SubmissionController::class, 'store'])
            ->middleware('active.course')
            ->name('submissions.store');

        // Withdraw Submission
        Route::delete('/submissions/{submission}', [SubmissionManagementController::class, 'destroy'])
            ->whereNumber('submission')
            ->middleware('active.course')
            ->name('submissions.destroy');

        // Download Materials
        Route::get('/materials/{material}/download', [EnrollmentController::class, 'downloadMaterial'])
            ->name('materials.download');

        // View PDF Materials
        Route::get('/materials/{material}/view', [EnrollmentController::class, 'viewMaterial'])
            ->name('materials.view');

    });

// Laravel Authentication Routes
require __DIR__.'/auth.php';