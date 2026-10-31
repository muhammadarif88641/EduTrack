<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SubmissionController extends Controller
{
    public function index(Assignment $assignment): View
    {
        abort_if($assignment->course->lecturer_id !== Auth::id(), 403);

        $submissions = $assignment->submissions()
            ->with('student')
            ->latest()
            ->get();

        return view('lecturer.submissions.index',
            compact('assignment', 'submissions'));
    }

    public function download(Submission $submission): BinaryFileResponse
    {
        abort_if($submission->assignment->course->lecturer_id !== Auth::id(), 403);

        if (!Storage::disk('local')->exists($submission->file_path)) {
            abort(404, 'Submission file not found.');
        }

        return response()->download(
            Storage::disk('local')->path($submission->file_path),
            $submission->original_filename
        );
    }

    public function grade(Request $request, Submission $submission): RedirectResponse
    {
        abort_if($submission->assignment->course->lecturer_id !== Auth::id(), 403);

        $validated = $request->validate([
            'marks_obtained' => [
                'required',
                'integer',
                'min:0',
                'max:' . $submission->assignment->max_marks,
            ],
            'feedback' => 'nullable|string|max:2000',
        ]);

        $submission->update([
            'marks_obtained' => $validated['marks_obtained'],
            'feedback' => $validated['feedback'] ?? null,
            'status' => 'graded',
        ]);

        return back()->with('success', 'Assignment graded successfully!');
    }
}
