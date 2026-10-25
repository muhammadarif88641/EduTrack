<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    // Show approved courses and the student's enrollment status
    public function index(): View
    {
        $courses = Course::where('status', 'approved')
            ->latest()
            ->get();

        $enrollments = Enrollment::where('student_id', Auth::id())
            ->get()
            ->keyBy('course_id');

        return view('student.courses.index', compact(
            'courses',
            'enrollments'
        ));
    }

    // Student requests enrollment in a course
    public function store(Course $course): RedirectResponse
    {
        // Only approved courses can receive enrollment requests
        if ($course->status !== 'approved') {
            abort(403, 'This course is not available for enrollment.');
        }

        // Prevent duplicate requests
        $existingEnrollment = Enrollment::where('student_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()
                ->route('student.courses.index')
                ->with('error', 'You have already requested this course.');
        }

        Enrollment::create([
            'student_id' => Auth::id(),
            'course_id' => $course->id,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('student.courses.index')
            ->with('success', 'Enrollment request submitted! Waiting for Admin approval.');
    }
}
