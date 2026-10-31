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

class SubmissionController extends Controller
{
    public function store(Request $request, Assignment $assignment): RedirectResponse
    {
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
            ->first();

        if ($existing && $existing->status === 'graded') {
            return back()->with('error', 'This assignment has already been graded.');
        }

        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $file = $request->file('file');

        $path = $file->store('submissions', 'local');

        $submittedAt = now();

        try {
            if ($existing) {
                $oldPath = $existing->file_path;

                $existing->update([
                    'file_path' => $path,
                    'original_filename' => $file->getClientOriginalName(),
                    'submitted_at' => $submittedAt,
                    'status' => 'submitted',
                    'marks_obtained' => null,
                    'feedback' => null,
                ]);

                if ($oldPath) {
                    Storage::disk('local')->delete($oldPath);
                }
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

        $isLate = $assignment->due_date
            && $submittedAt->greaterThan($assignment->due_date);

        if ($isLate) {
            return back()->with('success', 'Late assignment submitted successfully!');
        }

        return back()->with('success', 'Assignment submitted successfully!');
    }
}
