<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Course Workspace
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        <a href="{{ route('student.classes.index') }}"
           class="text-sm font-semibold text-blue-600 hover:text-blue-800">
            &larr; Back to My Courses
        </a>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-4 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-slate-900 text-white rounded-xl p-6">

            <p class="text-blue-300 text-sm font-semibold">
                {{ $course->course_code }}
            </p>

            <h1 class="text-3xl font-bold mt-2">
                {{ $course->course_name }}
            </h1>

            <p class="text-slate-300 mt-3">
                {{ $course->description ?? 'No description available.' }}
            </p>

            <span class="inline-block mt-4 bg-green-100 text-green-700 rounded-full px-3 py-1 text-xs font-semibold">
                Enrollment Approved
            </span>

        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="bg-white border rounded-xl p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    Learning Materials
                </p>

                <h2 class="text-3xl font-bold text-blue-600 mt-2">
                    {{ $materials->count() }}
                </h2>

            </div>

            <div class="bg-white border rounded-xl p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    Total Assignments
                </p>

                <h2 class="text-3xl font-bold text-gray-800 mt-2">
                    {{ $totalAssignments }}
                </h2>

            </div>

            <div class="bg-white border rounded-xl p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    Completed
                </p>

                <h2 class="text-3xl font-bold text-green-600 mt-2">
                    {{ $completedAssignments }}
                </h2>

            </div>

            <div class="bg-white border rounded-xl p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    Outstanding
                </p>

                <h2 class="text-3xl font-bold text-red-600 mt-2">
                    {{ $pendingAssignments }}
                </h2>

            </div>

        </div>

        <div class="bg-white border rounded-xl p-6 shadow-sm">

            <div class="flex justify-between items-center mb-3">

                <h2 class="text-lg font-bold text-gray-800">
                    Course Progress
                </h2>

                <span class="text-2xl font-bold text-blue-600">
                    {{ $progress }}%
                </span>

            </div>

            <progress
                value="{{ $progress }}"
                max="100"
                class="w-full h-4 rounded-full overflow-hidden accent-green-500">
            </progress>

            <p class="text-sm text-gray-500 mt-3">

                @if($totalAssignments > 0)

                    You have submitted {{ $completedAssignments }}
                    out of {{ $totalAssignments }} assignments.

                @else

                    No assignments have been published yet.

                @endif

            </p>

        </div>

        <div>

            <h2 class="text-xl font-bold text-gray-800">
                Weekly Learning Content
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Access learning materials and assignments by week.
            </p>

        </div>

        @foreach($weeks as $week)

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

                    @if($weekMaterials->isEmpty() && $weekAssignments->isEmpty())

                        <p class="text-sm text-gray-500">
                            No learning content available for this week.
                        </p>

                    @endif

                    @foreach($weekMaterials as $material)

                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">

                            <p class="text-xs font-semibold text-blue-600 uppercase">
                                Learning Material
                            </p>

                            <h3 class="font-bold text-gray-800 mt-1">
                                {{ $material->title }}
                            </h3>

                            <p class="text-sm text-gray-600 mt-2">
                                {{ $material->description ?? '' }}
                            </p>

                            <p class="text-sm text-gray-500 mt-2">
                                {{ $material->original_filename ?? 'Learning file' }}
                            </p>

                            <div class="flex flex-wrap gap-2 mt-4">

                                @if(strtolower(pathinfo($material->original_filename ?? $material->file_path, PATHINFO_EXTENSION)) === 'pdf')

                                    <a href="{{ route('student.materials.view', $material->id) }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                                        View PDF
                                    </a>

                                @endif

                                <a href="{{ route('student.materials.download', $material->id) }}"
                                   class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">
                                    Download
                                </a>

                            </div>

                        </div>

                    @endforeach

                    @foreach($weekAssignments as $assignment)

                        @php

                            $submission = $submissions->get($assignment->id);

                            $isOverdue = $assignment->due_date
                                && \Carbon\Carbon::parse($assignment->due_date)->lte(now());

                            $canWithdraw = $submission
                                && $submission->status === 'submitted'
                                && $submission->marks_obtained === null
                                && $submission->feedback === null
                                && !$isOverdue;

                        @endphp

                        <div class="bg-white border rounded-lg p-5">

                            <p class="text-xs font-semibold text-blue-600 uppercase">
                                Assignment
                            </p>

                            <div class="flex flex-wrap justify-between items-center gap-3 mt-2">

                                <h3 class="font-bold text-gray-800">
                                    {{ $assignment->title }}
                                </h3>

                                @if($submission)

                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">

                                        {{ $submission->marks_obtained !== null ? 'Graded' : 'Submitted' }}

                                    </span>

                                @elseif($isOverdue)

                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Overdue
                                    </span>

                                @else

                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Pending
                                    </span>

                                @endif

                            </div>

                            <p class="text-sm text-gray-600 mt-3 whitespace-pre-line">
                                {{ $assignment->instructions }}
                            </p>

                            <div class="text-sm text-gray-600 mt-4 space-y-1">

                                <p>
                                    Maximum Marks: {{ $assignment->max_marks }}
                                </p>

                                <p>

                                    Deadline:

                                    {{ $assignment->due_date
                                        ? \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, h:i A')
                                        : 'No deadline' }}

                                </p>

                            </div>

                            @if($submission)

                                <div class="mt-4 bg-gray-50 border rounded-lg p-4">

                                    <p class="text-sm text-gray-600">
                                        Submitted File:
                                        {{ $submission->original_filename }}
                                    </p>

                                    <p class="text-sm text-gray-600 mt-2">

                                        Submitted At:

                                        {{ \Carbon\Carbon::parse($submission->submitted_at)->format('d M Y, h:i A') }}

                                    </p>

                                    @if($assignment->due_date && \Carbon\Carbon::parse($submission->submitted_at)->gt(\Carbon\Carbon::parse($assignment->due_date)))

                                        <p class="text-sm font-semibold text-red-600 mt-2">
                                            Late Submission
                                        </p>

                                    @endif

                                    @if($submission->marks_obtained !== null)

                                        <p class="text-xl font-bold text-green-600 mt-3">

                                            {{ $submission->marks_obtained }}
                                            / {{ $assignment->max_marks }}

                                        </p>

                                        <p class="text-sm font-semibold text-gray-800 mt-3">
                                            Lecturer Feedback
                                        </p>

                                        <p class="text-sm text-gray-600 mt-1">

                                            {{ $submission->feedback ?? 'No feedback provided.' }}

                                        </p>

                                    @else

                                        <p class="text-sm font-semibold text-yellow-600 mt-3">
                                            Awaiting Grading
                                        </p>

                                    @endif

                                </div>

                            @endif

                            <div class="flex flex-wrap gap-3 mt-4">

                                <a href="{{ route('student.assignments.index', $course->id) }}"
                                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">

                                    {{ $submission ? 'Manage Submission' : 'Open Assignment' }}

                                </a>

                                @if($canWithdraw)

                                    <form method="POST"
                                          action="{{ route('student.submissions.destroy', $submission->id) }}"
                                          onsubmit="return confirm('Withdraw this submission? You will need to submit your assignment again.');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700">

                                            Withdraw Submission

                                        </button>

                                    </form>

                                @endif

                            </div>

                            @if($canWithdraw)

                                <p class="text-xs text-gray-500 mt-3">
                                    You can withdraw your submission before the deadline
                                    if it has not been reviewed or graded.
                                </p>

                            @endif

                        </div>

                    @endforeach

                </div>

            </details>

        @endforeach

    </div>

</x-app-layout>