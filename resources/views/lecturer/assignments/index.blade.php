
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Assignments - {{ $course->course_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 flex flex-wrap justify-between items-center gap-3">

                <div class="flex flex-wrap gap-3">

                    <a href="{{ route('lecturer.courses.index') }}"
                       class="inline-block bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Back to My Courses
                    </a>

                    <a href="{{ route('lecturer.progress.index', $course->id) }}"
                       class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        View Student Progress
                    </a>

                </div>

                <a href="{{ route('lecturer.assignments.create', $course) }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Create Assignment
                </a>

            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-xl font-bold mb-4">
                    Weekly Assignments
                </h3>

                @if($assignments->isEmpty())

                    <p class="text-gray-500">
                        No assignments have been created yet.
                    </p>

                @else

                    @php
                        $weeklyAssignments = $assignments->groupBy('week_number');
                    @endphp

                    <div class="space-y-4">

                        @for($week = 1; $week <= 14; $week++)

                            <details class="border rounded-lg overflow-hidden"
                                     {{ $week == 1 ? 'open' : '' }}>

                                <summary class="bg-gray-100 p-4 font-bold cursor-pointer hover:bg-gray-200">
                                    Week {{ $week }}
                                    ({{ $weeklyAssignments->get($week, collect())->count() }} Assignments)
                                </summary>

                                <div class="p-4 space-y-4">

                                    @forelse($weeklyAssignments->get($week, collect()) as $assignment)

                                        <div class="border rounded-lg p-4">

                                            <h4 class="text-lg font-bold">
                                                {{ $assignment->title }}
                                            </h4>

                                            <p class="text-gray-600 mt-2 whitespace-pre-line">{{ $assignment->instructions }}</p>

                                            <p class="mt-3">
                                                <strong>Maximum Marks:</strong>
                                                {{ $assignment->max_marks }}
                                            </p>

                                            <p class="mt-1">
                                                <strong>Due Date:</strong>
                                                {{ $assignment->due_date
                                                    ? $assignment->due_date->format('d M Y, h:i A')
                                                    : 'No due date' }}
                                            </p>

                                            <div class="mt-4">
                                                <a href="{{ route('lecturer.submissions.index', $assignment->id) }}"
                                                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                                    View Student Submissions
                                                </a>
                                            </div>

                                        </div>

                                    @empty

                                        <p class="text-gray-500">
                                            No assignments for this week.
                                        </p>

                                    @endforelse

                                </div>

                            </details>

                        @endfor

                    </div>

                @endif

            </div>

        </div>
    </div>
</x-app-layout>
