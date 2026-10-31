<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            EduTrack - My Classes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('student.dashboard') }}"
                   class="inline-block bg-gray-800 text-white px-4 py-2 rounded">
                    Back to Student Dashboard
                </a>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-xl font-bold mb-6">
                    My Enrolled Courses
                </h3>

                @if($enrollments->isEmpty())

                    <p class="text-gray-600 mb-4">
                        You have not joined any courses yet.
                    </p>

                    <a href="{{ route('student.courses.index') }}"
                       class="inline-block bg-gray-800 text-white px-4 py-2 rounded">
                        Find a Course
                    </a>

                @else

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        @foreach($enrollments as $enrollment)

                            @if($enrollment->course)

                                <div class="border rounded-lg p-5">

                                    <h4 class="font-bold text-lg">
                                        {{ $enrollment->course->course_name }}
                                    </h4>

                                    <p class="text-gray-600 mt-2">
                                        Course Code:
                                        {{ $enrollment->course->course_code }}
                                    </p>

                                    <p class="text-gray-700 mt-3">
                                        {{ $enrollment->course->description ?: 'No description available.' }}
                                    </p>

                                    <p class="text-green-700 font-semibold mt-4">
                                        Enrollment Approved
                                    </p>

                                    <!-- View Learning Materials Button -->
                                    <a href="{{ route('student.materials.index', $enrollment->course->id) }}"
                                       class="inline-block bg-gray-800 text-white px-4 py-2 rounded-lg mt-4 hover:bg-gray-700 transition">
                                        View Materials
                                    </a>

                                    <a href="{{ route('student.assignments.index', $enrollment->course->id) }}"
   class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg mt-4 ml-2 hover:bg-blue-700 transition">
    View Assignments
</a>

                                </div>

                            @endif

                        @endforeach

                    </div>

                @endif

            </div>

        </div>
    </div>
</x-app-layout>
