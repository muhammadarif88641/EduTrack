<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProgressController extends Controller
{
    public function index(Course $course): View
    {
        abort_if($course->lecturer_id !== Auth::id(), 403);

        $assignments = $course->assignments()
            ->orderBy('id')
            ->get();

        $enrollments = Enrollment::with('student')
            ->where('course_id', $course->id)
            ->where('status', 'approved')
            ->get();

        $studentIds = $enrollments->pluck('student_id');

        $assignmentIds = $assignments->pluck('id');

        $submissions = Submission::whereIn('student_id', $studentIds)
            ->whereIn('assignment_id', $assignmentIds)
            ->get()
            ->groupBy('student_id');

        return view('lecturer.progress.index', compact(
            'course',
            'assignments',
            'enrollments',
            'submissions'
        ));
    }
}
