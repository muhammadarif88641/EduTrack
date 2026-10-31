
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            EduTrack - My Progress
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('student.dashboard') }}"
                   class="inline-block bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    &larr; Back to Student Dashboard
                </a>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-2xl font-bold mb-2">
                    My Learning Progress
                </h3>

                <p class="text-gray-600 mb-6">
                    Track your assignment completion and marks across all enrolled courses.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

                    <div class="border rounded-lg p-5 bg-blue-50">
                        <p class="text-gray-600">Total Assignments</p>
                        <p class="text-3xl font-bold text-blue-700 mt-2">
                            {{ $totalAssignments }}
                        </p>
                    </div>

                    <div class="border rounded-lg p-5 bg-green-50">
                        <p class="text-gray-600">Completed</p>
                        <p class="text-3xl font-bold text-green-700 mt-2">
                            {{ $completedAssignments }}
                        </p>
                    </div>

                    <div class="border rounded-lg p-5 bg-red-50">
                        <p class="text-gray-600">Pending</p>
                        <p class="text-3xl font-bold text-red-700 mt-2">
                            {{ $pendingAssignments }}
                        </p>
                    </div>

                    <div class="border rounded-lg p-5 bg-gray-50">
                        <p class="text-gray-600">Completion Rate</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">
                            {{ $completionPercentage }}%
                        </p>
                    </div>

                </div>

                <h3 class="text-xl font-bold mb-5">
                    Progress by Course
                </h3>

                @forelse($enrollments as $enrollment)

                    @php
                        $course = $enrollment->course;
                        $assignments = $course->assignments;

                        $total = $assignments->count();

                        $completed = $assignments->filter(function ($assignment) use ($submissions) {
                            return $submissions->has($assignment->id);
                        })->count();

                        $percentage = $total > 0
                            ? round(($completed / $total) * 100)
                            : 0;
                    @endphp

                    <div class="border rounded-lg p-5 mb-5">

                        <div class="flex flex-wrap justify-between items-center gap-3">

                            <div>
                                <h4 class="text-lg font-bold text-gray-800">
                                    {{ $course->course_name }}
                                </h4>

                                <p class="text-gray-500 text-sm">
                                    {{ $course->course_code }}
                                </p>
                            </div>

                            <span class="font-bold text-green-700">
                                {{ $percentage }}%
                            </span>

                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-3 mt-4 overflow-hidden">
                            <progress value="{{ $percentage }}"
                                      max="100"
                                      class="w-full h-3 accent-green-600"></progress>
                        </div>

                        <p class="text-gray-600 text-sm mt-3">
                            {{ $completed }} of {{ $total }} assignments completed
                        </p>

                        @if($total === 0)
                            <p class="text-gray-500 text-sm mt-2">
                                No assignments have been created for this course.
                            </p>
                        @else

                            <div class="mt-5 space-y-3">

                                @foreach($assignments as $assignment)

                                    @php
                                        $submission = $submissions->get($assignment->id);

                                        $submittedLate = $submission
                                            && $assignment->due_date
                                            && $submission->submitted_at
                                            && $submission->submitted_at->greaterThan($assignment->due_date);
                                    @endphp

                                    <div class="border-t pt-3 flex flex-wrap justify-between gap-3">

                                        <div>
                                            <p class="font-semibold">
                                                {{ $assignment->title }}
                                            </p>

                                            <p class="text-gray-500 text-sm">
                                                Week {{ $assignment->week_number ?? 1 }}
                                            </p>

                                            
                                                <p class="text-gray-700 text-sm mt-1">
                                                    Marks:
                                                    {{ $submission->marks_obtained }}
                                                    / {{ $assignment->max_marks }}
                                                </p>

                                                <p class="text-gray-600 text-sm mt-1">
                                                    Feedback:
                                                    {{ $submission->feedback ?: 'No feedback provided.' }}
                                                </p>
                                            @endif
                                        </div>

                                        <div>
                                           @if($submission && $submittedLate)

                                            <span class="text-red-600 font-semibold">
                                                {{ $submission->status === 'graded'
                                                    ? 'Graded - Late Submission'
                                                    : 'Late Submission' }}
                                            </span>

                                        @elseif($submission && $submission->status === 'graded')

                                            <span class="text-green-700 font-semibold">
                                                Graded
                                            </span>

                                        @elseif($submission)

                                            <span class="text-green-600 font-semibold">
                                                Finish
                                            </span>

                                        @else

                                            <span class="text-red-600 font-semibold">
                                                Ongoing
                                            </span>

                                        @endif
                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @endif

                        <div class="mt-5">
                            <a href="{{ route('student.assignments.index', $course->id) }}"
                               class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                View Course Assignments
                            </a>
                        </div>@if($submission && $submission->status === 'graded')

                    </div>

                @empty

                    <p class="text-gray-500">
                        You have not joined any approved courses yet.
                    </p>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>
