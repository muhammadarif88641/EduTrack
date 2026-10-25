<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseApprovalController extends Controller
{
    // Display courses based on their approval status
    public function index(): View
    {
        $pendingCourses = Course::with('lecturer')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $approvedCourses = Course::with('lecturer')
            ->where('status', 'approved')
            ->latest()
            ->get();

        $rejectedCourses = Course::with('lecturer')
            ->where('status', 'rejected')
            ->latest()
            ->get();

        return view('admin.courses.index', compact(
            'pendingCourses',
            'approvedCourses',
            'rejectedCourses'
        ));
    }

    // Approve pending or previously rejected courses
    public function approve(Course $course): RedirectResponse
    {
        if (!in_array($course->status, ['pending', 'rejected'])) {
            abort(403);
        }

        $course->status = 'approved';
        $course->save();

        return redirect()
            ->route('admin.courses.index', ['tab' => 'approved'])
            ->with('success', 'Course approved successfully.');
    }

    // Reject a pending course
    public function reject(Course $course): RedirectResponse
    {
        if ($course->status !== 'pending') {
            abort(403);
        }

        $course->status = 'rejected';
        $course->save();

        return redirect()
            ->route('admin.courses.index', ['tab' => 'rejected'])
            ->with('success', 'Course rejected successfully.');
    }
}
