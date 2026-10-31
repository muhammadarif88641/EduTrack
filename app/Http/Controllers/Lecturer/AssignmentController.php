<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index(Course $course): View
    {
        abort_if($course->lecturer_id !== Auth::id(), 403);

        $assignments = $course->assignments()
            ->orderBy('week_number')
            ->latest()
            ->get();

        return view('lecturer.assignments.index',
            compact('course', 'assignments'));
    }

    public function create(Course $course): View
    {
        abort_if($course->lecturer_id !== Auth::id(), 403);

        return view('lecturer.assignments.create',
            compact('course'));
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        abort_if($course->lecturer_id !== Auth::id(), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'instructions' => 'required|string',
            'max_marks' => 'required|integer|min:1',
            'due_date' => 'nullable|date',
            'week_number' => 'required|integer|between:1,14',
        ]);

        $course->assignments()->create($validated);

        return redirect()
            ->route('lecturer.assignments.index', $course)
            ->with('success', 'Assignment created successfully!');
    }
}
