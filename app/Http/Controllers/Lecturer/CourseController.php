<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::where('lecturer_id', Auth::id())
            ->latest()
            ->get();

        return view('lecturer.courses.index', compact('courses'));
    }

    public function show(Course $course): View
    {
        abort_unless(
            $course->lecturer_id == Auth::id(),
            403
        );

        abort_unless(
            $course->status === 'approved',
            403,
            'This course is not active.'
        );

        $materials = DB::table('materials')
            ->where('course_id', $course->id)
            ->orderBy('week_number')
            ->get();

        $assignments = DB::table('assignments')
            ->where('course_id', $course->id)
            ->orderBy('week_number')
            ->get();

        $studentCount = DB::table('enrollments')
            ->where('course_id', $course->id)
            ->where('status', 'approved')
            ->distinct()
            ->count('student_id');

        $weeks = collect(range(1, 14))
            ->merge($materials->pluck('week_number'))
            ->merge($assignments->pluck('week_number'))
            ->unique()
            ->sort()
            ->values();

        $weeklyMaterials = $materials->groupBy('week_number');

        $weeklyAssignments = $assignments->groupBy('week_number');

        return view('lecturer.courses.show', compact(
            'course',
            'materials',
            'assignments',
            'studentCount',
            'weeks',
            'weeklyMaterials',
            'weeklyAssignments'
        ));
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