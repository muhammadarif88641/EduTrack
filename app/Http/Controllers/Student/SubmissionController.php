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

        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($assignment->due_date && now()->greaterThan($assignment->due_date)) {
            return back()->with('error', 'The assignment deadline has passed.');
        }

        $existing = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', Auth::id())
            ->first();

        if ($existing && $existing->status === 'graded') {
            return back()->with('error', 'This assignment has already been graded.');
        }

        $file = $request->file('file');

        $path = $file->store('submissions', 'local');

        if ($existing) {
            Storage::disk('local')->delete($existing->file_path);

            $existing->update([
                'file_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'submitted_at' => now(),
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
                'submitted_at' => now(),
                'status' => 'submitted',
            ]);
        }

        return back()->with('success', 'Assignment submitted successfully!');
    }
}
