
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            EduTrack - My Assignments
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
                    My Assignments
                </h3>

                <p class="text-gray-600 mb-6">
                    View assignments from all your enrolled courses.
                </p>

                @if($assignments->isEmpty())

                    <p class="text-gray-500">
                        No assignments available yet.
                    </p>

                @else

                    <div class="space-y-4">

                        @foreach($assignments as $assignment)

                            @php
                                $submission = $submissions->get($assignment->id);

                                $isLate = $assignment->due_date
                                    && now()->greaterThan($assignment->due_date);

                                $submittedLate = $submission
                                    && $assignment->due_date
                                    && $submission->submitted_at
                                    && $submission->submitted_at->greaterThan($assignment->due_date);
                            @endphp

                            <div class="border rounded-lg p-5">

                                <div class="flex flex-wrap justify-between gap-3">

                                    <div>
                                        <h4 class="text-lg font-bold text-blue-700">
                                            {{ $assignment->title }}
                                        </h4>

                                        <p class="text-gray-600 mt-1">
                                            Course: {{ $assignment->course->course_name }}
                                        </p>

                                        <p class="text-gray-500 text-sm mt-1">
                                            {{ $assignment->course->course_code }}
                                        </p>
                                    </div>

                                    <div>
                                       @if($submission)

                                            @if($submittedLate)
                                                <span class="text-red-600 font-semibold">
                                                    {{ $submission->status === 'graded'
                                                        ? 'Graded - Late Submission'
                                                        : 'Late Submission' }}
                                                </span>

                                            @elseif($submission->status === 'graded')
                                                <span class="text-green-700 font-semibold">
                                                    Graded
                                                </span>

                                            @else
                                                <span class="text-green-600 font-semibold">
                                                    Submitted
                                                </span>
                                            @endif

                                        @elseif($isLate)

                                            <span class="text-red-600 font-semibold">
                                                Overdue - Not Submitted
                                            </span>

                                        @else

                                            <span class="text-yellow-700 font-semibold">
                                                Pending
                                            </span>

                              @endif

            </div>
        </div>
    </div>
</x-app-layout>
