<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MaterialManagementController extends Controller
{
    public function edit(int $material): View
    {
        $record = $this->getMaterial($material);

        $course = DB::table('courses')
            ->where('id', $record->course_id)
            ->first();

        return view('lecturer.materials.edit', [
            'material' => $record,
            'course' => $course,
        ]);
    }

    public function update(
        Request $request,
        int $material
    ): RedirectResponse
    {
        $record = $this->getMaterial($material);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'week_number' => 'required|integer|min:1|max:52',
        ]);

        DB::table('materials')
            ->where('id', $record->id)
            ->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'week_number' => $validated['week_number'],
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('lecturer.courses.show', $record->course_id)
            ->with('success', 'Learning material updated successfully.');
    }

    public function destroy(int $material): RedirectResponse
    {
        $record = $this->getMaterial($material);

        DB::table('materials')
            ->where('id', $record->id)
            ->delete();

        return redirect()
            ->route('lecturer.courses.show', $record->course_id)
            ->with('success', 'Learning material removed successfully.');
    }

    private function getMaterial(int $id)
    {
        $material = DB::table('materials')
            ->where('id', $id)
            ->first();

        abort_if(!$material, 404, 'Learning material not found.');

        $course = DB::table('courses')
            ->where('id', $material->course_id)
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

        return $material;
    }
}