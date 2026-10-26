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

    // Approve a course created by a lecturer
    public function approve(Course $course): RedirectResponse
    {
        if (!in_array($course->status, ['pending', 'rejected'])) {
            return redirect()
                ->route('admin.courses.index')
                ->with('error', 'This course cannot be approved.');
        }

        $course->update([
            'status' => 'approved',
        ]);

        return redirect()
            ->route('admin.courses.index', ['tab' => 'approved'])
            ->with('success', 'Course approved successfully.');
    }

    // Reject a pending course
    public function reject(Course $course): RedirectResponse
    {
        if ($course->status !== 'pending') {
            return redirect()
                ->route('admin.courses.index')
                ->with('error', 'Only pending courses can be rejected.');
        }

        $course->update([
            'status' => 'rejected',
        ]);

        return redirect()
            ->route('admin.courses.index', ['tab' => 'rejected'])
            ->with('success', 'Course rejected successfully.');
    }
}
