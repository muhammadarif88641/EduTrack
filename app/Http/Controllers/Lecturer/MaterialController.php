<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MaterialController extends Controller
{
    // Show learning materials for a lecturer's course
    public function index(Course $course): View
    {
        // Make sure the lecturer owns this course
        if ($course->lecturer_id !== Auth::id()) {
            abort(403, 'You do not have permission to access this course.');
        }

        $materials = Material::where('course_id', $course->id)
            ->latest()
            ->get();

        return view('lecturer.materials.index', compact(
            'course',
            'materials'
        ));
    }

    // Show the upload material form
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

    // Save a new learning material
    public function store(Request $request, Course $course): RedirectResponse
    {
        if ($course->lecturer_id !== Auth::id()) {
            abort(403, 'You do not have permission to upload materials.');
        }

        if ($course->status !== 'approved') {
            abort(403, 'This course must be approved before uploading materials.');
        }

        // Validate the material information
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        // Store PDF in private Laravel storage
        $file = $request->file('file');

        $path = $file->store(
            'materials/' . $course->id,
            'local'
        );

        // Save material information to database
        try {
            Material::create([
                'course_id' => $course->id,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'file_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Storage::disk('local')->delete($path);
            throw $e;
        }

        return redirect()
            ->route('lecturer.materials.index', $course)
            ->with('success', 'Learning material uploaded successfully.');
    }
}
