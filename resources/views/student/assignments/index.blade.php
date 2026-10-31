
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Assignments - {{ $course->course_name }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('student.classes.index') }}"
                   class="inline-block px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                    &larr; Back to My Classes
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-2xl font-bold mb-2">
                    {{ $course->course_name }}
                </h3>

                <p class="text-gray-600 mb-6">
                    Course Code: {{ $course->course_code }}
                </p>

                <h3 class="text-xl font-semibold mb-4">
                    Available Assignments
                </h3>

                @forelse ($assignments as $assignment)

                    @php
                        $submission = $submissions->get($assignment->id);
                        $isLate = $assignment->due_date
                            && now()->greaterThan($assignment->due_date);
                        $isGraded = $submission
                            && $submission->status === 'graded';
                    @endphp

                    <div class="border rounded-lg p-5 mb-4">

                        <h4 class="text-lg font-bold text-blue-700">
                            {{ $assignment->title }}
                        </h4>

                        <p class="text-gray-700 mt-3 whitespace-pre-line">{{ $assignment->instructions }}</p>

                        <p class="mt-4">
                            <strong>Maximum Marks:</strong>
                            {{ $assignment->max_marks }}
                        </p>

                        <p class="mt-2">
                            <strong>Due Date:</strong>
                            {{ $assignment->due_date
                                ? $assignment->due_date->format('d M Y, h:i A')
                                : 'No due date' }}
                        </p>

                        <div class="mt-5 border-t pt-4">

                            @if($submission)

                                <p class="text-green-700 font-semibold">
                                    Submission Status:
                                    {{ ucfirst($submission->status) }}
                                </p>

                                <p class="text-gray-600 mt-2">
                                    Submitted File:
                                    {{ $submission->original_filename }}
                                </p>

                                <p class="text-gray-600 mt-2">
                                    Submitted At:
                                    {{ $submission->submitted_at->format('d M Y, h:i A') }}
                                </p>

                                @if($isGraded)
                                    <p class="mt-3 font-semibold">
                                        Marks:
                                        {{ $submission->marks_obtained }}
                                        / {{ $assignment->max_marks }}
                                    </p>

                                    <p class="mt-2">
                                        Feedback:
                                        {{ $submission->feedback ?: 'No feedback provided.' }}
                                    </p>
                                @endif

                            @else

                                <p class="text-red-600 font-semibold">
                                    Not Submitted
                                </p>

                            @endif

                            @if(!$isLate && !$isGraded)

                                <form method="POST"
                                      action="{{ route('student.submissions.store', $assignment->id) }}"
                                      enctype="multipart/form-data"
                                      class="mt-4">

                                    @csrf

                                    <label class="block font-semibold mb-2">
                                        Upload Assignment (PDF or Word)
                                    </label>

                                    <input type="file"
                                           name="file"
                                           accept=".pdf,.doc,.docx"
                                           required
                                           class="block w-full border rounded-lg p-2">

                                    @error('file')
                                        <p class="text-red-600 text-sm mt-2">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                    <button type="submit"
                                            class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        {{ $submission ? 'Replace Submission' : 'Submit Assignment' }}
                                    </button>

                                    <p class="text-gray-500 text-sm mt-2">
                                        Maximum file size: 10 MB
                                    </p>

                                </form>

                            @elseif($isLate && !$submission)

                                <p class="text-red-600 mt-3">
                                    Submission deadline has passed.
                                </p>

                            @endif

                        </div>

                    </div>

                @empty

                    <p class="text-gray-500">
                        No assignments available for this course yet.
                    </p>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>
