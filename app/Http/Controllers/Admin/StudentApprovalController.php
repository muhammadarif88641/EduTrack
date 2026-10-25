<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentApprovalController extends Controller
{
    public function index(): View
    {
        $pendingStudents = User::where('role', 'student')
            ->where('account_status', 'pending')
            ->latest()
            ->get();

        $approvedStudents = User::where('role', 'student')
            ->where('account_status', 'approved')
            ->latest()
            ->get();

        $rejectedStudents = User::where('role', 'student')
            ->where('account_status', 'rejected')
            ->latest()
            ->get();

        return view('admin.students.index', compact(
            'pendingStudents',
            'approvedStudents',
            'rejectedStudents'
        ));
    }

    public function approve(User $student): RedirectResponse
    {
        if ($student->role !== 'student' ||
            !in_array($student->account_status, ['pending', 'rejected'])) {
            abort(403);
        }

        $student->account_status = 'approved';
        $student->save();

        return redirect()
            ->route('admin.students.index', ['tab' => 'approved'])
            ->with('success', 'Student approved successfully.');
    }

    public function reject(User $student): RedirectResponse
    {
        if ($student->role !== 'student' ||
            $student->account_status !== 'pending') {
            abort(403);
        }

        $student->account_status = 'rejected';
        $student->save();

        return redirect()
            ->route('admin.students.index', ['tab' => 'rejected'])
            ->with('success', 'Student rejected successfully.');
    }
}