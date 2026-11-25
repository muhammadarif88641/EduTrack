<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AcademicRiskService
{
    public function getRows(
        ?int $lecturerId = null,
        ?int $studentId = null
    ) {
        $now = now();

        $query = DB::table('enrollments')
            ->join('users as students',
                'enrollments.student_id', '=', 'students.id')
            ->join('courses',
                'enrollments.course_id', '=', 'courses.id')
            ->join('users as lecturers',
                'courses.lecturer_id', '=', 'lecturers.id')
            ->leftJoin('assignments',
                'courses.id', '=', 'assignments.course_id')
            ->leftJoin('submissions', function ($join) {
                $join->on(
                    'assignments.id', '=', 'submissions.assignment_id'
                )->on(
                    'students.id', '=', 'submissions.student_id'
                );
            })
            ->where('enrollments.status', 'approved')
            ->where('courses.status', 'approved')
            ->where('students.role', 'student');

        if ($lecturerId !== null) {
            $query->where('courses.lecturer_id', $lecturerId);
        }

        if ($studentId !== null) {
            $query->where('students.id', $studentId);
        }

        $rows = $query->select(
                'students.id as student_id',
                'students.name as student_name',
                'students.email as student_email',
                'courses.id as course_id',
                'courses.course_code',
                'courses.course_name',
                'lecturers.name as lecturer_name'
            )
            ->selectRaw('COUNT(assignments.id) as total_assignments')
            ->selectRaw('COUNT(submissions.id) as completed_assignments')

            ->selectRaw('
                SUM(CASE
                    WHEN assignments.due_date <= ?
                    THEN 1 ELSE 0
                END) as due_assignments
            ', [$now])

            ->selectRaw('
                SUM(CASE
                    WHEN assignments.due_date <= ?
                    AND submissions.id IS NOT NULL
                    THEN 1 ELSE 0
                END) as completed_due
            ', [$now])

            ->selectRaw('
                SUM(CASE
                    WHEN assignments.due_date <= ?
                    AND submissions.id IS NULL
                    THEN 1 ELSE 0
                END) as overdue_assignments
            ', [$now])

            ->selectRaw('
                SUM(CASE
                    WHEN submissions.marks_obtained IS NOT NULL
                    AND assignments.max_marks > 0
                    THEN submissions.marks_obtained
                    ELSE 0
                END) as obtained_marks
            ')

            ->selectRaw('
                SUM(CASE
                    WHEN submissions.marks_obtained IS NOT NULL
                    AND assignments.max_marks > 0
                    THEN assignments.max_marks
                    ELSE 0
                END) as possible_marks
            ')

            ->groupBy(
                'students.id',
                'students.name',
                'students.email',
                'courses.id',
                'courses.course_code',
                'courses.course_name',
                'lecturers.name'
            )
            ->orderBy('students.name')
            ->get();

        return $rows->map(function ($row) {

            $row->pending_assignments =
                $row->total_assignments -
                $row->completed_assignments;

            $row->completion_percentage =
                $row->total_assignments > 0
                ? round(
                    $row->completed_assignments /
                    $row->total_assignments * 100, 1
                )
                : 0;

            $dueCompletion = $row->due_assignments > 0
                ? $row->completed_due /
                    $row->due_assignments * 100
                : null;

            $gradePercentage = $row->possible_marks > 0
                ? $row->obtained_marks /
                    $row->possible_marks * 100
                : null;

            $row->due_completion_percentage =
                $dueCompletion !== null
                ? round($dueCompletion, 1)
                : null;

            $row->average_marks =
                $gradePercentage !== null
                ? round($gradePercentage, 1)
                : null;

            $row->risk_status = $this->classify(
                $dueCompletion,
                $gradePercentage,
                (int) $row->overdue_assignments
            );

            return $row;
        });
    }

    private function classify(
        ?float $completion,
        ?float $marks,
        int $overdue
    ): string {
        if ($completion === null && $marks === null) {
            return 'Insufficient Data';
        }

        if (
            ($completion !== null && $completion < 50) ||
            ($marks !== null && $marks < 50) ||
            $overdue >= 3
        ) {
            return 'At Risk';
        }

        if (
            ($completion !== null && $completion < 80) ||
            ($marks !== null && $marks < 75) ||
            $overdue >= 1
        ) {
            return 'Needs Attention';
        }

        return 'On Track';
    }
}