<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ArchiveController extends Controller
{
    public function index(): View
    {
        $courses = Course::with('lecturer')
            ->where('status', 'archived')
            ->latest('updated_at')
            ->get();

        return view('admin.archive.index', compact('courses'));
    }

    public function archive(Course $course): RedirectResponse
    {
        $updated = Course::where('id', $course->id)
            ->where('status', 'approved')
            ->update([
                'status' => 'archived',
            ]);

        if (!$updated) {
            return redirect()
                ->route('admin.courses.index')
                ->with('error', 'Only approved courses can be archived.');
        }

        return redirect()
            ->route('admin.archive.index')
            ->with('success', 'Course archived successfully. All academic records have been preserved.');
    }

    public function restore(Course $course): RedirectResponse
    {
        $updated = Course::where('id', $course->id)
            ->where('status', 'archived')
            ->update([
                'status' => 'approved',
            ]);

        if (!$updated) {
            return redirect()
                ->route('admin.archive.index')
                ->with('error', 'Only archived courses can be restored.');
        }

        return redirect()
            ->route('admin.archive.index')
            ->with('success', 'Course restored successfully.');
    }
}