<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ArchiveController extends Controller
{
    public function index(): View
    {
        $courses = Course::where('lecturer_id', Auth::id())
            ->where('status', 'archived')
            ->latest('updated_at')
            ->get();

        return view('lecturer.archive.index', compact('courses'));
    }

    public function show(Course $course): View
    {
        abort_unless(
            $course->lecturer_id == Auth::id(),
            403
        );

        abort_unless(
            $course->status === 'archived',
            403,
            'This course is not archived.'
        );

        $materials = DB::table('materials')
            ->where('course_id', $course->id)
            ->orderBy('week_number')
            ->get();

        $assignments = DB::table('assignments')
            ->where('course_id', $course->id)
            ->orderBy('week_number')
            ->get();

        $students = DB::table('enrollments')
            ->join('users', 'enrollments.student_id', '=', 'users.id')
            ->where('enrollments.course_id', $course->id)
            ->where('enrollments.status', 'approved')
            ->select(
                'users.id',
                'users.name',
                'users.email'
            )
            ->orderBy('users.name')
            ->get();

        $submissions = DB::table('submissions')
            ->join(
                'assignments',
                'submissions.assignment_id',
                '=',
                'assignments.id'
            )
            ->join(
                'users',
                'submissions.student_id',
                '=',
                'users.id'
            )
            ->where('assignments.course_id', $course->id)
            ->select(
                'submissions.*',
                'assignments.title as assignment_title',
                'assignments.max_marks',
                'users.name as student_name'
            )
            ->orderBy('users.name')
            ->get();

        $totalStudents = $students->count();

        $totalMaterials = $materials->count();

        $totalAssignments = $assignments->count();

        $totalSubmissions = $submissions->count();

        $gradedSubmissions = $submissions
            ->whereNotNull('marks_obtained');

        $averageMarks = $gradedSubmissions
            ->filter(function ($submission) {
                return $submission->max_marks > 0;
            })
            ->avg(function ($submission) {
                return ($submission->marks_obtained
                    / $submission->max_marks) * 100;
            });

        $averageMarks = $averageMarks !== null
            ? round($averageMarks, 1)
            : null;

        return view('lecturer.archive.show', compact(
            'course',
            'materials',
            'assignments',
            'students',
            'submissions',
            'totalStudents',
            'totalMaterials',
            'totalAssignments',
            'totalSubmissions',
            'averageMarks'
        ));
    }
}