<?php

namespace App\Http\Controllers;

use App\Services\AcademicRiskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(
        Request $request,
        AcademicRiskService $riskService
    ) {
        return view(
            'reports.index',
            $this->reportData($request, $riskService)
        );
    }

    public function export(
        Request $request,
        AcademicRiskService $riskService
    ) {
        $data = $this->reportData($request, $riskService);

        $filename = 'EduTrack-' . $data['filters']['report']
            . '-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($data) {

            $file = fopen('php://output', 'w');

            fwrite($file, "\xEF\xBB\xBF");

            if ($data['filters']['report'] === 'details') {

                $this->writeCsv($file, [
                    'Student Name',
                    'Email',
                    'Course Code',
                    'Course Name',
                    'Assignment',
                    'Week',
                    'Due Date',
                    'Submitted At',
                    'Marks Obtained',
                    'Maximum Marks',
                    'Submission Status',
                ]);

                foreach ($data['details'] as $row) {

                    $this->writeCsv($file, [
                        $row->student_name,
                        $row->student_email,
                        $row->course_code,
                        $row->course_name,
                        $row->assignment_title,
                        $row->week_number,
                        $row->due_date,
                        $row->submitted_at,
                        $row->marks_obtained,
                        $row->max_marks,
                        $row->display_status,
                    ]);
                }

            } else {

                $this->writeCsv($file, [
                    'Student Name',
                    'Email',
                    'Course Code',
                    'Course Name',
                    'Lecturer',
                    'Total Assignments',
                    'Completed',
                    'Outstanding',
                    'Overdue',
                    'Completion (%)',
                    'Due Completion (%)',
                    'Weighted Marks (%)',
                    'Academic Risk',
                ]);

                foreach ($data['rows'] as $row) {

                    $this->writeCsv($file, [
                        $row->student_name,
                        $row->student_email,
                        $row->course_code,
                        $row->course_name,
                        $row->lecturer_name,
                        $row->total_assignments,
                        $row->completed_assignments,
                        $row->pending_assignments,
                        $row->overdue_assignments,
                        $row->completion_percentage,
                        $row->due_completion_percentage,
                        $row->average_marks,
                        $row->risk_status,
                    ]);
                }
            }

            fclose($file);

        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store',
        ]);
    }

    private function reportData(
        Request $request,
        AcademicRiskService $riskService
    ): array {
        $role = Auth::user()->role;

        abort_unless(
            in_array($role, ['admin', 'lecturer']),
            403
        );

        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'course_id' => 'nullable|integer|min:1',
            'risk' => 'nullable|in:On Track,Needs Attention,At Risk,Insufficient Data',
            'report' => 'nullable|in:summary,details',
        ]);

        $filters = [
            'search' => trim($validated['search'] ?? ''),
            'course_id' => $validated['course_id'] ?? '',
            'risk' => $validated['risk'] ?? '',
            'report' => $validated['report'] ?? 'summary',
        ];

        $lecturerId = $role === 'lecturer'
            ? Auth::id()
            : null;

        $rows = $riskService->getRows($lecturerId);

        $coursesQuery = DB::table('courses')
            ->where('status', 'approved');

        if ($lecturerId !== null) {

            $coursesQuery->where(
                'lecturer_id',
                $lecturerId
            );
        }

        $courses = $coursesQuery
            ->orderBy('course_name')
            ->get();

        if ($filters['search'] !== '') {

            $search = $filters['search'];

            $rows = $rows->filter(function ($row) use ($search) {

                return mb_stripos($row->student_name, $search) !== false
                    || mb_stripos($row->student_email, $search) !== false
                    || mb_stripos($row->course_name, $search) !== false
                    || mb_stripos($row->course_code, $search) !== false;
            });
        }

        if ($filters['course_id'] !== '') {

            $rows = $rows->where(
                'course_id',
                $filters['course_id']
            );
        }

        if ($filters['risk'] !== '') {

            $rows = $rows->where(
                'risk_status',
                $filters['risk']
            );
        }

        $rows = $rows->values();

        $total = $rows->sum('total_assignments');

        $completed = $rows->sum('completed_assignments');

        $possibleMarks = $rows->sum('possible_marks');

        $stats = [
            'students' => $rows
                ->pluck('student_id')->unique()->count(),

            'enrollments' => $rows->count(),

            'courses' => $rows
                ->pluck('course_id')->unique()->count(),

            'completed' => $completed,

            'outstanding' => $rows
                ->sum('pending_assignments'),

            'overdue' => $rows
                ->sum('overdue_assignments'),

            'at_risk' => $rows
                ->where('risk_status', 'At Risk')
                ->pluck('student_id')->unique()->count(),

            'completion' => $total > 0
                ? round($completed / $total * 100, 1)
                : null,

            'average_marks' => $possibleMarks > 0
                ? round(
                    $rows->sum('obtained_marks')
                    / $possibleMarks * 100, 1
                )
                : null,
        ];

        $details = collect();

        if ($filters['report'] === 'details') {

            $details = $this->assignmentDetails(
                $rows,
                $lecturerId
            );
        }

        return compact(
            'role',
            'filters',
            'courses',
            'rows',
            'details',
            'stats'
        );
    }

    private function assignmentDetails($rows, $lecturerId)
    {
        if ($rows->isEmpty()) {
            return collect();
        }

        $allowed = $rows->keyBy(function ($row) {
            return $row->student_id . '-' . $row->course_id;
        });

        $query = DB::table('enrollments')
            ->join(
                'users',
                'enrollments.student_id',
                '=',
                'users.id'
            )
            ->join(
                'courses',
                'enrollments.course_id',
                '=',
                'courses.id'
            )
            ->join(
                'assignments',
                'courses.id',
                '=',
                'assignments.course_id'
            )
            ->leftJoin('submissions', function ($join) {

                $join->on(
                    'assignments.id',
                    '=',
                    'submissions.assignment_id'
                )->on(
                    'users.id',
                    '=',
                    'submissions.student_id'
                );
            })
            ->where('enrollments.status', 'approved')
            ->where('courses.status', 'approved')
            ->where('users.role', 'student')
            ->whereIn(
                'courses.id',
                $rows->pluck('course_id')->unique()
            );

        if ($lecturerId !== null) {

            $query->where(
                'courses.lecturer_id',
                $lecturerId
            );
        }

        return $query->select(
                'users.id as student_id',
                'users.name as student_name',
                'users.email as student_email',
                'courses.id as course_id',
                'courses.course_code',
                'courses.course_name',
                'assignments.title as assignment_title',
                'assignments.week_number',
                'assignments.due_date',
                'assignments.max_marks',
                'submissions.id as submission_id',
                'submissions.submitted_at',
                'submissions.marks_obtained',
                'submissions.status as submission_status'
            )
            ->orderBy('users.name')
            ->orderBy('courses.course_code')
            ->orderBy('assignments.week_number')
            ->get()
            ->filter(function ($row) use ($allowed) {

                return $allowed->has(
                    $row->student_id . '-' . $row->course_id
                );
            })
            ->map(function ($row) {

                if ($row->submission_id !== null) {

                    $row->display_status = ucfirst(
                        $row->submission_status
                    );

                } elseif (
                    $row->due_date &&
                    \Carbon\Carbon::parse($row->due_date)->lte(now())
                ) {

                    $row->display_status = 'Overdue';

                } else {

                    $row->display_status = 'Not Submitted';
                }

                return $row;
            })
            ->values();
    }

    private function writeCsv($file, array $values): void
    {
        $values = array_map(function ($value) {

            if (
                is_string($value) &&
                preg_match('/^[\s]*[=+\-@]|^[\t\r\n]/u', $value)
            ) {
                return "'" . $value;
            }

            return $value;

        }, $values);

        fputcsv($file, $values, ',', '"', '');
    }
}