<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProgressController extends Controller
{
    public function index(): View
    {
        $enrollments = Enrollment::with([
            'course.assignments'
        ])
            ->where('student_id', Auth::id())
            ->where('status', 'approved')
            ->whereHas('course', function ($query) {
                $query->where('status', 'approved');
            })
            ->get();

        $assignmentIds = $enrollments
            ->flatMap(function ($enrollment) {
                return $enrollment->course->assignments->pluck('id');
            });

        $submissions = Submission::where('student_id', Auth::id())
            ->whereIn('assignment_id', $assignmentIds)
            ->get()
            ->keyBy('assignment_id');

        $totalAssignments = $assignmentIds->count();

        $completedAssignments = $submissions->count();

        $pendingAssignments = max(
            0,
            $totalAssignments - $completedAssignments
        );

        $completionPercentage = $totalAssignments > 0
            ? round(($completedAssignments / $totalAssignments) * 100)
            : 0;

        $gradedAssignments = $submissions->where('status', 'graded')->count();

        return view('student.progress.index', compact(
            'enrollments',
            'submissions',
            'totalAssignments',
            'completedAssignments',
            'pendingAssignments',
            'completionPercentage',
            'gradedAssignments'
        ));
    }
}
