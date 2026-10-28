
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
