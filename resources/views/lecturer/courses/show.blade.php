<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Course Workspace
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        <a href="{{ route('lecturer.courses.index') }}"
           class="text-sm font-semibold text-blue-600 hover:text-blue-800">
            &larr; Back to My Courses
        </a>

        <div class="bg-slate-900 text-white rounded-xl p-6">

            <div class="flex flex-wrap items-center justify-between gap-4">

                <div>

                    <p class="text-blue-300 text-sm font-semibold">
                        {{ $course->course_code }}
                    </p>

                    <h1 class="text-3xl font-bold mt-2">
                        {{ $course->course_name }}
                    </h1>

                    <p class="text-slate-300 mt-3">
                        {{ $course->description ?? 'No description available.' }}
                    </p>

                </div>

                <span class="bg-green-100 text-green-700 rounded-full px-4 py-2 text-sm font-semibold">
                    Active Course
                </span>

            </div>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <div class="bg-white rounded-xl shadow-sm border p-5">

                <p class="text-sm text-gray-500">
                    Learning Materials
                </p>

                <h2 class="text-3xl font-bold text-blue-600 mt-2">
                    {{ $materials->count() }}
                </h2>

            </div>

            <div class="bg-white rounded-xl shadow-sm border p-5">

                <p class="text-sm text-gray-500">
                    Total Assignments
                </p>

                <h2 class="text-3xl font-bold text-blue-600 mt-2">
                    {{ $assignments->count() }}
                </h2>

            </div>

            <div class="bg-white rounded-xl shadow-sm border p-5">

                <p class="text-sm text-gray-500">
                    Enrolled Students
                </p>

                <h2 class="text-3xl font-bold text-green-600 mt-2">
                    {{ $studentCount }}
                </h2>

            </div>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <h2 class="text-lg font-bold text-gray-800 mb-4">
                Course Management
            </h2>

            <div class="flex flex-wrap gap-3">

                <a href="{{ route('lecturer.materials.create', $course->id) }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    + Add Material
                </a>

                <a href="{{ route('lecturer.assignments.create', $course->id) }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    + Add Assignment
                </a>

                <a href="{{ route('lecturer.progress.index', $course->id) }}"
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Student Progress
                </a>

                <a href="{{ route('lecturer.materials.index', $course->id) }}"
                   class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-100">
                    Manage Materials
                </a>

                <a href="{{ route('lecturer.assignments.index', $course->id) }}"
                   class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-100">
                    Manage Assignments
                </a>

            </div>

        </div>

        <div>

            <h2 class="text-xl font-bold text-gray-800">
                Weekly Course Content
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                View learning materials and assignments by week.
            </p>

        </div>

        @foreach ($weeks as $week)

            @php
                $weekMaterials = $weeklyMaterials->get($week, collect());
                $weekAssignments = $weeklyAssignments->get($week, collect());
            @endphp

            <details class="bg-white border rounded-xl shadow-sm"
                     @if($loop->first) open @endif>

                <summary class="cursor-pointer p-5 font-semibold text-gray-800">

                    Week {{ $week }}

                    <span class="ml-3 text-sm font-normal text-gray-500">

                        {{ $weekMaterials->count() }} materials |
                        {{ $weekAssignments->count() }} assignments

                    </span>

                </summary>

                <div class="border-t p-5 space-y-4">

                    @if ($weekMaterials->isEmpty() && $weekAssignments->isEmpty())

                        <p class="text-gray-500 text-sm">
                            No learning content has been added for this week.
                        </p>

                    @endif

                    @foreach ($weekMaterials as $material)

                        <div class="border border-blue-100 bg-blue-50 rounded-lg p-4">

                            <p class="text-xs font-semibold text-blue-600 uppercase">
                                Learning Material
                            </p>

                            <h3 class="font-bold text-gray-800 mt-1">
                                {{ $material->title }}
                            </h3>

                            <p class="text-sm text-gray-600 mt-1">
                                {{ $material->description ?? '' }}
                            </p>

                            <p class="text-sm text-blue-700 mt-2">
                                {{ $material->original_filename ?? 'Uploaded file' }}
                            </p>

                        </div>

                    @endforeach

                    @foreach ($weekAssignments as $assignment)

                        <div class="border border-green-100 bg-green-50 rounded-lg p-4">

                            <div class="flex flex-wrap justify-between items-center gap-3">

                                <div>

                                    <p class="text-xs font-semibold text-green-700 uppercase">
                                        Assignment
                                    </p>

                                    <h3 class="font-bold text-gray-800 mt-1">
                                        {{ $assignment->title }}
                                    </h3>

                                    <p class="text-sm text-gray-600 mt-2">
                                        Maximum Marks: {{ $assignment->max_marks }}
                                    </p>

                                    <p class="text-sm text-gray-600">

                                        Due:
                                        {{ $assignment->due_date
                                            ? \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, h:i A')
                                            : 'No deadline' }}

                                    </p>

                                </div>

                                <a href="{{ route('lecturer.submissions.index', $assignment->id) }}"
                                   class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">

                                    View Submissions

                                </a>

                            </div>

                        </div>

                    @endforeach

                </div>

            </details>

        @endforeach

    </div>

</x-app-layout>