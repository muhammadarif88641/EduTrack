<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Material;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class EnrollmentController extends Controller
{
    // Show approved courses and student's enrollment status
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

    // Show courses that the student has successfully joined
    public function myClasses(): View
    {
        $enrollments = Enrollment::with('course')
            ->where('student_id', Auth::id())
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('student.classes.index', compact('enrollments'));
    }

    // Show learning materials for an enrolled course
    public function materials(Course $course): View
    {
        // Student must have an approved enrollment
        $isEnrolled = Enrollment::where('student_id', Auth::id())
            ->where('course_id', $course->id)
            ->where('status', 'approved')
            ->exists();

        if (!$isEnrolled || $course->status !== 'approved') {
            abort(403, 'You do not have permission to view these materials.');
        }

        // Get materials belonging to this course
        $materials = Material::where('course_id', $course->id)
            ->latest()
            ->get();

        return view('student.materials.index', compact(
            'course',
            'materials'
        ));
    }

    // Download a PDF material
    public function downloadMaterial(Material $material): BinaryFileResponse
    {
        $course = $material->course;

        if (!$course || $course->status !== 'approved') {
            abort(403);
        }

        // Check approved enrollment
        $isEnrolled = Enrollment::where('student_id', Auth::id())
            ->where('course_id', $course->id)
            ->where('status', 'approved')
            ->exists();

        if (!$isEnrolled) {
            abort(403, 'You are not enrolled in this course.');
        }

        // Check whether the file exists
        if (!Storage::disk('local')->exists($material->file_path)) {
            abort(404, 'Material file not found.');
        }

        return response()->download(
            Storage::disk('local')->path($material->file_path),
            $material->original_filename ?: 'material.pdf'
        );
    }

    
    // Allow approved students to view PDF materials in browser
    public function viewMaterial(Material $material): BinaryFileResponse
    {
        $course = $material->course;

        // Check that the course is approved
        if (!$course || $course->status !== 'approved') {
            abort(403, 'This course is not available.');
        }

        // Check that student is enrolled and approved
        $isEnrolled = Enrollment::where('student_id', Auth::id())
            ->where('course_id', $course->id)
            ->where('status', 'approved')
            ->exists();

        if (!$isEnrolled) {
            abort(403, 'You do not have permission to view this PDF.');
        }

        // Check that the PDF exists in private storage
        if (!Storage::disk('local')->exists($material->file_path)) {
            abort(404, 'PDF file not found.');
        }

        // Display PDF in the browser instead of downloading
        return response()->file(
            Storage::disk('local')->path($material->file_path),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline',
                'X-Content-Type-Options' => 'nosniff',
            ]
        );
    }

}
