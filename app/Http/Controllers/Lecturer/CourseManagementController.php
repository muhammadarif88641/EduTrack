<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CourseManagementController extends Controller
{
    public function edit(int $course)
    {
        $course = $this->getCourse($course);

        return view('lecturer.courses.edit', compact('course'));
    }

    public function update(Request $request, int $course)
    {
        $course = $this->getCourse($course);

        $validated = $request->validate([
            'course_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('courses', 'course_code')
                    ->ignore($course->id),
            ],
            'course_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DB::table('courses')
            ->where('id', $course->id)
            ->update([
                'course_code' => $validated['course_code'],
                'course_name' => $validated['course_name'],
                'description' => $validated['description'] ?? null,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('lecturer.courses.show', $course->id)
            ->with('success', 'Course updated successfully.');
    }

    private function getCourse(int $id)
    {
        $course = DB::table('courses')
            ->where('id', $id)
            ->first();

        abort_if(!$course, 404);

        abort_unless(
            (int) $course->lecturer_id === (int) Auth::id(),
            403
        );

        abort_unless($course->status === 'approved', 403);

        return $course;
    }
}