<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubmissionManagementController extends Controller
{
    public function destroy(int $submission): RedirectResponse
    {
        return DB::transaction(function () use ($submission) {

            $record = DB::table('submissions')
                ->where('id', $submission)
                ->lockForUpdate()
                ->first();

            abort_if(!$record, 404, 'Submission not found.');

            abort_unless(
                $record->student_id == Auth::id(),
                403,
                'You do not own this submission.'
            );

            $assignment = DB::table('assignments')
                ->where('id', $record->assignment_id)
                ->first();

            abort_if(!$assignment, 404);

            $course = DB::table('courses')
                ->where('id', $assignment->course_id)
                ->first();

            abort_if(!$course, 404);

            abort_unless(
                $course->status === 'approved',
                403,
                'Inactive course records cannot be modified.'
            );

            $isEnrolled = DB::table('enrollments')
                ->where('student_id', Auth::id())
                ->where('course_id', $course->id)
                ->where('status', 'approved')
                ->exists();

            abort_unless($isEnrolled, 403);

            if (
                $record->status !== 'submitted' ||
                $record->marks_obtained !== null ||
                $record->feedback !== null
            ) {

                return redirect()
                    ->route('student.courses.show', $course->id)
                    ->with('error', 'Reviewed or graded submissions cannot be withdrawn.');

            }

            if (
                $assignment->due_date &&
                Carbon::parse($assignment->due_date)->lte(now())
            ) {

                return redirect()
                    ->route('student.courses.show', $course->id)
                    ->with('error', 'The submission deadline has passed.');

            }

            DB::table('submissions')
                ->where('id', $record->id)
                ->delete();

            return redirect()
                ->route('student.courses.show', $course->id)
                ->with('success', 'Assignment submission withdrawn successfully.');

        });
    }
}