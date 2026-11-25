<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubmissionController extends Controller
{
    public function store(Request $request, Assignment $assignment): RedirectResponse
    {
        $assignment = Assignment::whereKey($assignment->id)
            ->lockForUpdate()
            ->firstOrFail();

        $course = $assignment->course;

        if (!$course || $course->status !== 'approved') {
            abort(403, 'This course is not available.');
        }

        $isEnrolled = Enrollment::where('student_id', Auth::id())
            ->where('course_id', $course->id)
            ->where('status', 'approved')
            ->exists();

        if (!$isEnrolled) {
            abort(403, 'You are not enrolled in this course.');
        }

        $existing = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', Auth::id())
            ->lockForUpdate()
            ->first();

        if ($existing && (
            $existing->status !== 'submitted' ||
            $existing->marks_obtained !== null ||
            $existing->feedback !== null
        )) {

            return back()->with(
                'error',
                'Reviewed or graded assignments cannot be resubmitted.'
            );

        }

        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $submittedAt = now();

        if (
            $existing &&
            $assignment->due_date &&
            Carbon::parse($assignment->due_date)->lte($submittedAt)
        ) {

            return back()->with(
                'error',
                'You cannot replace your submission after the deadline.'
            );

        }

        $file = $request->file('file');

        $path = $file->store('submissions', 'local');

        if (!$path) {
            return back()->with('error', 'File upload failed. Please try again.');
        }

        $oldPath = $existing?->file_path;

        try {

            if ($existing) {

                $existing->update([
                    'file_path' => $path,
                    'original_filename' => $file->getClientOriginalName(),
                    'submitted_at' => $submittedAt,
                    'status' => 'submitted',
                    'marks_obtained' => null,
                    'feedback' => null,
                ]);

            } else {

                Submission::create([
                    'assignment_id' => $assignment->id,
                    'student_id' => Auth::id(),
                    'file_path' => $path,
                    'original_filename' => $file->getClientOriginalName(),
                    'submitted_at' => $submittedAt,
                    'status' => 'submitted',
                ]);

            }

        } catch (\Throwable $e) {

            Storage::disk('local')->delete($path);

            throw $e;

        }

        if ($oldPath && $oldPath !== $path) {

            DB::afterCommit(function () use ($oldPath) {

                try {
                    Storage::disk('local')->delete($oldPath);
                } catch (\Throwable $e) {
                    report($e);
                }

            });

        }

        $isLate = $assignment->due_date
            && $submittedAt->greaterThan(
                Carbon::parse($assignment->due_date)
            );

        if ($existing) {

            return back()->with(
                'success',
                'Assignment resubmitted successfully!'
            );

        }

        if ($isLate) {

            return back()->with(
                'success',
                'Late assignment submitted successfully!'
            );

        }

        return back()->with(
            'success',
            'Assignment submitted successfully!'
        );
    }
}