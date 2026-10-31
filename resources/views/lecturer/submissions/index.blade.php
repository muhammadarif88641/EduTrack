
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Student Submissions - {{ $assignment->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('lecturer.assignments.index', $assignment->course_id) }}"
                   class="text-blue-600 hover:underline">
                    Back to Assignments
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 text-red-800 p-4 mb-4 rounded">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-bold mb-2">
                    {{ $assignment->title }}
                </h3>

                <p class="text-gray-600 mb-6">
                    Maximum Marks: {{ $assignment->max_marks }}
                </p>

                @if ($submissions->isEmpty())
                    <p class="text-gray-500">
                        No students have submitted this assignment yet.
                    </p>
                @else

                    <div class="space-y-6">
                        @foreach ($submissions as $submission)

                            <div class="border rounded-lg p-5">

                                <h4 class="text-lg font-semibold">
                                    {{ $submission->student->name }}
                                </h4>

                                <p class="text-gray-600 mt-1">
                                    File: {{ $submission->original_filename }}
                                </p>

                                <p class="text-gray-600 mt-1">
                                    Submitted:
                                    {{ $submission->submitted_at->format('d M Y, h:i A') }}
                                </p>

                                <p class="mt-2">
                                    <strong>Status:</strong>

                                    @if ($submission->status === 'graded')
                                        <span class="text-green-600 font-semibold">
                                            Graded
                                        </span>
                                    @else
                                        <span class="text-orange-600 font-semibold">
                                            Submitted
                                        </span>
                                    @endif
                                </p>

                                <div class="mt-4">
                                    <a href="{{ route('lecturer.submissions.download', $submission->id) }}"
                                       class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                        Download Submission
                                    </a>
                                </div>

                                <form method="POST"
                                      action="{{ route('lecturer.submissions.grade', $submission->id) }}"
                                      class="mt-6 border-t pt-4">

                                    @csrf
                                    @method('PATCH')

                                    <label class="block font-semibold mb-2">
                                        Marks Obtained
                                    </label>

                                    <input type="number"
                                           name="marks_obtained"
                                           min="0"
                                           max="{{ $assignment->max_marks }}"
                                           value="{{ $submission->marks_obtained }}"
                                           required
                                           class="border-gray-300 rounded w-full mb-4">

                                    <label class="block font-semibold mb-2">
                                        Lecturer Feedback
                                    </label>

                                    <textarea name="feedback"
                                              rows="3"
                                              class="border-gray-300 rounded w-full mb-4">{{ $submission->feedback }}</textarea>

                                    <button type="submit"
                                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                        Save Grade
                                    </button>

                                </form>

                            </div>

                        @endforeach
                    </div>

                @endif

            </div>
        </div>
    </div>
</x-app-layout>
