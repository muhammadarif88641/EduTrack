<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::where('lecturer_id', Auth::id())
            ->latest()
            ->get();

        return view('lecturer.courses.index', compact('courses'));
    }

    public function selectFeature(string $feature): View
    {
        abort_unless(
            in_array($feature, ['materials', 'assignments', 'progress'], true),
            404
        );

        $courses = Course::where('lecturer_id', Auth::id())
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('lecturer.courses.select-feature', compact(
            'courses',
            'feature'
        ));
    }

    public function create(): View
    {
        return view('lecturer.courses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course_code' => 'required|string|max:50|unique:courses,course_code',
            'course_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Course::create([
            'lecturer_id' => Auth::id(),
            'course_code' => $validated['course_code'],
            'course_name' => $validated['course_name'],
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('lecturer.courses.index')
            ->with('success', 'Course created successfully! Waiting for Admin approval.');
    }
}
