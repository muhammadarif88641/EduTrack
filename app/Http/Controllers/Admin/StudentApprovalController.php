<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentApprovalController extends Controller
{
    // Display all pending students
    public function index(): View
    {
        $students = User::where('role', 'student')
            ->where('account_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.students.index', compact('students'));
    }

    // Approve a student
    public function approve(User $student): RedirectResponse
    {
        abort_unless(
            $student->role === 'student' &&
            $student->account_status === 'pending',
            404
        );

        $student->account_status = 'approved';
        $student->save();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student approved successfully.');
    }

    // Reject a student
    public function reject(User $student): RedirectResponse
    {
        abort_unless(
            $student->role === 'student' &&
            $student->account_status === 'pending',
            404
        );

        $student->account_status = 'rejected';
        $student->save();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student rejected successfully.');
    }
}