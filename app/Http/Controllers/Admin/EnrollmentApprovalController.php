<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EnrollmentApprovalController extends Controller
{
    // Show all enrollment requests
    public function index(): View
    {
        $pendingEnrollments = Enrollment::with(['student', 'course'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $approvedEnrollments = Enrollment::with(['student', 'course'])
            ->where('status', 'approved')
            ->latest()
            ->get();

        $rejectedEnrollments = Enrollment::with(['student', 'course'])
            ->where('status', 'rejected')
            ->latest()
            ->get();

        return view('admin.enrollments.index', compact(
            'pendingEnrollments',
            'approvedEnrollments',
            'rejectedEnrollments'
        ));
    }

    // Approve pending or rejected enrollments
    public function approve(Enrollment $enrollment): RedirectResponse
    {
        if (!in_array($enrollment->status, ['pending', 'rejected'])) {
            return redirect()
                ->route('admin.enrollments.index')
                ->with('error', 'This enrollment cannot be approved.');
        }

        $enrollment->update([
            'status' => 'approved',
        ]);

        return redirect()
            ->route('admin.enrollments.index', ['tab' => 'approved'])
            ->with('success', 'Enrollment approved successfully.');
    }

    // Reject pending enrollment requests
    public function reject(Enrollment $enrollment): RedirectResponse
    {
        if ($enrollment->status !== 'pending') {
            return redirect()
                ->route('admin.enrollments.index')
                ->with('error', 'Only pending enrollments can be rejected.');
        }

        $enrollment->update([
            'status' => 'rejected',
        ]);

        return redirect()
            ->route('admin.enrollments.index', ['tab' => 'rejected'])
            ->with('success', 'Enrollment rejected successfully.');
    }
}
