<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class EnrollmentRemovalController extends Controller
{
    public function destroy(int $enrollment): RedirectResponse
    {
        return DB::transaction(function () use ($enrollment) {

            $record = DB::table('enrollments')
                ->where('id', $enrollment)
                ->lockForUpdate()
                ->first();

            abort_if(!$record, 404);

            $course = DB::table('courses')
                ->where('id', $record->course_id)
                ->first();

            abort_if(!$course, 404);

            if ($course->status !== 'approved') {

                return redirect()
                    ->route('admin.enrollments.index')
                    ->with('error', 'Inactive course records cannot be deleted.');

            }

            if (!in_array($record->status, ['pending', 'rejected'], true)) {

                return redirect()
                    ->route('admin.enrollments.index')
                    ->with('error', 'Approved enrolments cannot be deleted.');

            }

            $hasSubmissions = DB::table('submissions')
                ->join(
                    'assignments',
                    'submissions.assignment_id',
                    '=',
                    'assignments.id'
                )
                ->where('assignments.course_id', $record->course_id)
                ->where('submissions.student_id', $record->student_id)
                ->exists();

            if ($hasSubmissions) {

                return redirect()
                    ->route('admin.enrollments.index')
                    ->with('error', 'This enrolment contains academic records and cannot be deleted.');

            }

            DB::table('enrollments')
                ->where('id', $enrollment)
                ->delete();

            return redirect()
                ->route('admin.enrollments.index')
                ->with('success', 'Enrolment request deleted successfully.');

        });
    }
}