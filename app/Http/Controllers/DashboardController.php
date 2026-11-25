<?php

namespace App\Http\Controllers;

use App\Services\AcademicRiskService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function admin(AcademicRiskService $riskService): View
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $rows = $riskService->getRows();

        $stats = [
            'students' => DB::table('users')
                ->where('role', 'student')->count(),

            'lecturers' => DB::table('users')
                ->where('role', 'lecturer')->count(),

            'pending_students' => DB::table('users')
                ->where('role', 'student')
                ->where('account_status', 'pending')->count(),

            'active_courses' => DB::table('courses')
                ->where('status', 'approved')->count(),

            'pending_courses' => DB::table('courses')
                ->where('status', 'pending')->count(),

            'archived_courses' => DB::table('courses')
                ->where('status', 'archived')->count(),

            'pending_enrollments' => DB::table('enrollments')
                ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                ->where('enrollments.status', 'pending')
                ->where('courses.status', 'approved')->count(),

            'approved_enrollments' => $rows->count(),

            'at_risk_students' => $rows
                ->where('risk_status', 'At Risk')
                ->pluck('student_id')->unique()->count(),
        ];

        $riskCounts = $this->riskCounts($rows);

        $submissionCounts = $this->submissionCounts($rows);

        $coursePerformance = $this->coursePerformance($rows);

        return view('dashboards.admin', compact(
            'stats',
            'rows',
            'riskCounts',
            'submissionCounts',
            'coursePerformance'
        ));
    }

    public function lecturer(AcademicRiskService $riskService): View
    {
        abort_unless(Auth::user()->role === 'lecturer', 403);

        $lecturerId = Auth::id();

        $rows = $riskService->getRows($lecturerId);

        $courses = DB::table('courses')
            ->where('lecturer_id', $lecturerId)
            ->get();

        $activeCourseIds = $courses
            ->where('status', 'approved')
            ->pluck('id');

        $stats = [
            'total_courses' => $courses->count(),

            'active_courses' => $courses
                ->where('status', 'approved')->count(),

            'pending_courses' => $courses
                ->where('status', 'pending')->count(),

            'archived_courses' => $courses
                ->where('status', 'archived')->count(),

            'students' => $rows
                ->pluck('student_id')->unique()->count(),

            'materials' => DB::table('materials')
                ->whereIn('course_id', $activeCourseIds)->count(),

            'assignments' => DB::table('assignments')
                ->whereIn('course_id', $activeCourseIds)->count(),

            'ungraded_submissions' => DB::table('submissions')
                ->join('assignments',
                    'submissions.assignment_id', '=', 'assignments.id')
                ->join('enrollments', function ($join) {
                    $join->on(
                        'enrollments.course_id', '=', 'assignments.course_id'
                    )->on(
                        'enrollments.student_id', '=', 'submissions.student_id'
                    );
                })
                ->whereIn('assignments.course_id', $activeCourseIds)
                ->where('enrollments.status', 'approved')
                ->whereNull('submissions.marks_obtained')
                ->count(),

            'at_risk_students' => $rows
                ->where('risk_status', 'At Risk')
                ->pluck('student_id')->unique()->count(),
        ];

        $riskCounts = $this->riskCounts($rows);

        $submissionCounts = $this->submissionCounts($rows);

        $coursePerformance = $this->coursePerformance($rows);

        return view('dashboards.lecturer', compact(
            'stats',
            'rows',
            'courses',
            'riskCounts',
            'submissionCounts',
            'coursePerformance'
        ));
    }

    public function student(AcademicRiskService $riskService): View
    {
        abort_unless(Auth::user()->role === 'student', 403);

        $rows = $riskService->getRows(null, Auth::id());

        $totalAssignments = $rows->sum('total_assignments');

        $completedAssignments = $rows->sum('completed_assignments');

        $possibleMarks = $rows->sum('possible_marks');

        $stats = [
            'courses' => $rows->count(),

            'total_assignments' => $totalAssignments,

            'completed_assignments' => $completedAssignments,

            'pending_assignments' => $rows->sum('pending_assignments'),

            'overdue_assignments' => $rows->sum('overdue_assignments'),

            'completion_percentage' => $totalAssignments > 0
                ? round($completedAssignments / $totalAssignments * 100, 1)
                : 0,

            'average_marks' => $possibleMarks > 0
                ? round($rows->sum('obtained_marks') / $possibleMarks * 100, 1)
                : null,
        ];

        $riskCounts = $this->riskCounts($rows);

        $submissionCounts = $this->submissionCounts($rows);

        $coursePerformance = $this->coursePerformance($rows);

        return view('dashboards.student', compact(
            'stats',
            'rows',
            'riskCounts',
            'submissionCounts',
            'coursePerformance'
        ));
    }

    private function riskCounts($rows): array
    {
        return [
            'On Track' => $rows
                ->where('risk_status', 'On Track')->count(),

            'Needs Attention' => $rows
                ->where('risk_status', 'Needs Attention')->count(),

            'At Risk' => $rows
                ->where('risk_status', 'At Risk')->count(),

            'Insufficient Data' => $rows
                ->where('risk_status', 'Insufficient Data')->count(),
        ];
    }

    private function submissionCounts($rows): array
    {
        return [
            'Completed' => (int) $rows->sum('completed_assignments'),

            'Overdue' => (int) $rows->sum('overdue_assignments'),

            'Outstanding' => max(
                0,
                (int) $rows->sum('pending_assignments')
                - (int) $rows->sum('overdue_assignments')
            ),
        ];
    }

    private function coursePerformance($rows)
    {
        return $rows->groupBy('course_id')
            ->map(function ($items) {

                $course = $items->first();

                $total = $items->sum('total_assignments');

                $completed = $items->sum('completed_assignments');

                $possible = $items->sum('possible_marks');

                return [
                    'course_id' => $course->course_id,

                    'course_code' => $course->course_code,

                    'course_name' => $course->course_name,

                    'students' => $items->count(),

                    'completion' => $total > 0
                        ? round($completed / $total * 100, 1)
                        : null,

                    'average_marks' => $possible > 0
                        ? round($items->sum('obtained_marks') / $possible * 100, 1)
                        : null,

                    'at_risk' => $items
                        ->where('risk_status', 'At Risk')->count(),
                ];
            })->values();
    }
}