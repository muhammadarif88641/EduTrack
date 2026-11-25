<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AssignmentManagementController extends Controller
{
    public function edit(int $assignment): View
    {
        $record = $this->getAssignment($assignment);

        $course = DB::table('courses')
            ->where('id', $record->course_id)
            ->first();

        $hasSubmissions = DB::table('submissions')
            ->where('assignment_id', $record->id)
            ->exists();

        return view('lecturer.assignments.edit', [
            'assignment' => $record,
            'course' => $course,
            'hasSubmissions' => $hasSubmissions,
        ]);
    }

    public function update(
        Request $request,
        int $assignment
    ): RedirectResponse
    {
        $record = $this->getAssignment($assignment);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'instructions' => 'required|string',
            'week_number' => 'required|integer|min:1|max:52',
            'max_marks' => 'required|integer|min:1|max:1000',
            'due_date' => 'nullable|date',
        ]);

        $hasSubmissions = DB::table('submissions')
            ->where('assignment_id', $record->id)
            ->exists();

        if ($hasSubmissions &&
            (int) $validated['max_marks'] !== (int) $record->max_marks) {

            return back()
                ->withInput()
                ->withErrors([
                    'max_marks' => 'Maximum marks cannot be changed because students have already submitted this assignment.',
                ]);

        }

        DB::table('assignments')
            ->where('id', $record->id)
            ->update([
                'title' => $validated['title'],
                'instructions' => $validated['instructions'],
                'week_number' => $validated['week_number'],
                'max_marks' => $validated['max_marks'],
                'due_date' => $validated['due_date']
                    ? \Carbon\Carbon::parse($validated['due_date'])
                    : null,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('lecturer.courses.show', $record->course_id)
            ->with('success', 'Assignment updated successfully.');
    }

    public function destroy(int $assignment): RedirectResponse
    {
        $record = $this->getAssignment($assignment);

        $hasSubmissions = DB::table('submissions')
            ->where('assignment_id', $record->id)
            ->exists();

        if ($hasSubmissions) {

            return redirect()
                ->route('lecturer.courses.show', $record->course_id)
                ->with('error', 'This assignment cannot be deleted because it contains student submissions.');

        }

        DB::table('assignments')
            ->where('id', $record->id)
            ->delete();

        return redirect()
            ->route('lecturer.courses.show', $record->course_id)
            ->with('success', 'Assignment deleted successfully.');
    }

    private function getAssignment(int $id)
    {
        $assignment = DB::table('assignments')
            ->where('id', $id)
            ->first();

        abort_if(!$assignment, 404, 'Assignment not found.');

        $course = DB::table('courses')
            ->where('id', $assignment->course_id)
            ->first();

        abort_if(!$course, 404, 'Course not found.');

        abort_unless(
            $course->lecturer_id == Auth::id(),
            403,
            'You do not own this course.'
        );

        abort_unless(
            $course->status === 'approved',
            403,
            'Inactive course records cannot be modified.'
        );

        return $assignment;
    }
}