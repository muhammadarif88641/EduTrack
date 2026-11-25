<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Course Workspace
        </h2>
    </x-slot>

        <div class="max-w-7xl mx-auto px-6 pt-6">

            <div class="flex justify-end">

                <a href="{{ route('lecturer.courses.edit', $course->id) }}"
                class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15.232 5.232l3.536 3.536M4 20l4.5-1 12-12a2.5 2.5 0 00-3.536-3.536l-12 12L4 20z" />

                    </svg>

                    Edit Course

                </a>

            </div>

        </div>

        <div class="bg-white border rounded-xl p-5 shadow-sm">

            <h2 class="text-lg font-bold text-gray-800 mb-4">
                Course Management
            </h2>

            <div class="flex flex-wrap gap-3">

                <a href="{{ route('lecturer.materials.create', $course->id) }}"
                   class="bg-blue-600 text-white px-5 py-3 rounded-lg font-semibold hover:bg-blue-700">

                    + Add Material

                </a>

                <a href="{{ route('lecturer.assignments.create', $course->id) }}"
                   class="bg-blue-600 text-white px-5 py-3 rounded-lg font-semibold hover:bg-blue-700">

                    + Add Assignment

                </a>

                <a href="{{ route('lecturer.progress.index', $course->id) }}"
                   class="bg-green-600 text-white px-5 py-3 rounded-lg font-semibold hover:bg-green-700">

                    Student Progress

                </a>

                <a href="{{ route('lecturer.materials.index', $course->id) }}"
                   class="bg-gray-200 text-gray-700 px-5 py-3 rounded-lg hover:bg-gray-300">

                    All Materials

                </a>

                <a href="{{ route('lecturer.assignments.index', $course->id) }}"
                   class="bg-gray-200 text-gray-700 px-5 py-3 rounded-lg hover:bg-gray-300">

                    All Assignments

                </a>

            </div>

        </div>

        <div>

            <h2 class="text-xl font-bold text-gray-800">
                Weekly Learning Content
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Manage course materials and assignments by week.
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

                        <p class="text-sm text-gray-500">
                            No learning content available for this week.
                        </p>

                    @endif

                    @foreach ($weekMaterials as $material)

                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-5">

                            <p class="text-xs font-semibold text-blue-600 uppercase">
                                Learning Material
                            </p>

                            <h3 class="font-bold text-gray-800 mt-2">
                                {{ $material->title }}
                            </h3>

                            <p class="text-sm text-gray-600 mt-2">
                                {{ $material->description ?? 'No description available.' }}
                            </p>

                            <p class="text-sm text-gray-500 mt-3">

                                File: {{ $material->original_filename ?? 'Learning file' }}

                            </p>

                            <div class="flex flex-wrap gap-2 mt-4">

                                <a href="{{ route('lecturer.materials.edit', $material->id) }}"
                                   class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">

                                    Edit Material

                                </a>

                                <form method="POST"
                                      action="{{ route('lecturer.materials.destroy', $material->id) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this learning material?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700">

                                        Delete Material

                                    </button>

                                </form>

                            </div>

                        </div>

                    @endforeach

                    @foreach ($weekAssignments as $assignment)

                        <div class="bg-white border rounded-lg p-5">

                            <p class="text-xs font-semibold text-blue-600 uppercase">
                                Assignment
                            </p>

                            <h3 class="font-bold text-gray-800 mt-2">
                                {{ $assignment->title }}
                            </h3>

                            <p class="text-sm text-gray-600 mt-3 whitespace-pre-line">
                                {{ $assignment->instructions }}
                            </p>

                            <div class="text-sm text-gray-600 mt-4 space-y-2">

                                <p>
                                    Maximum Marks:
                                    {{ $assignment->max_marks }}
                                </p>

                                <p>

                                    Deadline:

                                    {{ $assignment->due_date
                                        ? \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, h:i A')
                                        : 'No deadline' }}

                                </p>

                            </div>

                            <div class="flex flex-wrap gap-2 mt-4">

                                <a href="{{ route('lecturer.submissions.index', $assignment->id) }}"
                                class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-700">

                                    View Submissions

                                </a>

                                <a href="{{ route('lecturer.assignments.edit', $assignment->id) }}"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">

                                    Edit Assignment

                                </a>

                                <form method="POST"
                                    action="{{ route('lecturer.assignments.destroy', $assignment->id) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this assignment?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700">

                                        Delete Assignment

                                    </button>

                                </form>

                            </div>

                        </div>

                    @endforeach

                </div>

            </details>

        @endforeach

    </div>

</x-app-layout>