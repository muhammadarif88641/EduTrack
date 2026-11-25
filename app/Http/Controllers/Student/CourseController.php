<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function show(Course $course): View
    {
        $studentId = Auth::id();

        abort_unless(
            $course->status === 'approved',
            403,
            'This course is not active.'
        );

        $isEnrolled = DB::table('enrollments')
            ->where('student_id', $studentId)
            ->where('course_id', $course->id)
            ->where('status', 'approved')
            ->exists();

        abort_unless(
            $isEnrolled,
            403,
            'You are not enrolled in this course.'
        );

        $materials = DB::table('materials')
            ->where('course_id', $course->id)
            ->orderBy('week_number')
            ->get();

        $assignments = DB::table('assignments')
            ->where('course_id', $course->id)
            ->orderBy('week_number')
            ->get();

        $submissions = DB::table('submissions')
            ->where('student_id', $studentId)
            ->whereIn('assignment_id', $assignments->pluck('id'))
            ->get()
            ->keyBy('assignment_id');

        $totalAssignments = $assignments->count();

        $completedAssignments = $assignments
            ->filter(function ($assignment) use ($submissions) {
                return $submissions->has($assignment->id);
            })
            ->count();

        $pendingAssignments =
            $totalAssignments - $completedAssignments;

        $progress = $totalAssignments > 0
            ? round(($completedAssignments / $totalAssignments) * 100, 1)
            : 0;

        $weeks = collect(range(1, 14))
            ->merge($materials->pluck('week_number'))
            ->merge($assignments->pluck('week_number'))
            ->unique()
            ->sort()
            ->values();

        $weeklyMaterials = $materials->groupBy('week_number');

        $weeklyAssignments = $assignments->groupBy('week_number');

        return view('student.courses.show', compact(
            'course',
            'materials',
            'assignments',
            'submissions',
            'totalAssignments',
            'completedAssignments',
            'pendingAssignments',
            'progress',
            'weeks',
            'weeklyMaterials',
            'weeklyAssignments'
        ));
    }
}