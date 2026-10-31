<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(Course $course): View
    {
        $isEnrolled = Enrollment::where('student_id', Auth::id())
            ->where('course_id', $course->id)
            ->where('status', 'approved')
            ->exists();

        if (!$isEnrolled || $course->status !== 'approved') {
            abort(403, 'You do not have permission to view these assignments.');
        }

        $assignments = $course->assignments()
            ->latest()
            ->get();

        $submissions = Submission::where('student_id', Auth::id())
            ->whereIn('assignment_id', $assignments->pluck('id'))
            ->get()
            ->keyBy('assignment_id');

        return view('student.assignments.index', compact(
            'course',
            'assignments',
            'submissions'
        ));
    }
}
