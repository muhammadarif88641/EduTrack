<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MaterialController extends Controller
{
    public function index(Course $course): View
    {
        if ($course->lecturer_id !== Auth::id()) {
            abort(403, 'You do not have permission to access this course.');
        }

        $materials = Material::where('course_id', $course->id)
            ->orderBy('week_number')
            ->latest()
            ->get();

        return view('lecturer.materials.index', compact(
            'course',
            'materials'
        ));
    }

    public function create(Course $course): View
    {
        if ($course->lecturer_id !== Auth::id()) {
            abort(403, 'You do not have permission to upload materials.');
        }

        if ($course->status !== 'approved') {
            abort(403, 'This course must be approved before uploading materials.');
        }

        return view('lecturer.materials.create', compact('course'));
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        if ($course->lecturer_id !== Auth::id()) {
            abort(403, 'You do not have permission to upload materials.');
        }

        if ($course->status !== 'approved') {
            abort(403, 'This course must be approved before uploading materials.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'week_number' => ['required', 'integer', 'between:1,14'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $file = $request->file('file');

        $path = $file->store(
            'materials/' . $course->id,
            'local'
        );

        try {
            Material::create([
                'course_id' => $course->id,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'week_number' => $validated['week_number'],
                'file_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
            ]);
        } catch (\Throwable $e) {
            Storage::disk('local')->delete($path);
            throw $e;
        }

        return redirect()
            ->route('lecturer.materials.index', $course)
            ->with('success', 'Learning material uploaded successfully.');
    }
}
