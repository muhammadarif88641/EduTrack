
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            @if($feature === 'materials')
                Select Course - Learning Materials
            @elseif($feature === 'assignments')
                Select Course - Assignments
            @else
                Select Course - Student Progress
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('lecturer.dashboard') }}"
                   class="inline-block bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Back to Lecturer Dashboard
                </a>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-xl font-bold mb-2">
                    Select a Course
                </h3>

                <p class="text-gray-600 mb-6">
                    Choose a course to continue.
                </p>

                @if($courses->isEmpty())

                    <p class="text-gray-500">
                        No approved courses available yet.
                    </p>

                    <a href="{{ route('lecturer.courses.index') }}"
                       class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Go to My Courses
                    </a>

                @else

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        @foreach($courses as $course)

                            <div class="border rounded-lg p-5">

                                <h4 class="text-lg font-bold text-gray-800">
                                    {{ $course->course_name }}
                                </h4>

                                <p class="text-gray-600 mt-2">
                                    Course Code: {{ $course->course_code }}
                                </p>

                                <p class="text-green-700 text-sm mt-2">
                                    Approved
                                </p>

                                <div class="mt-4">

                                    @if($feature === 'materials')

                                        <a href="{{ route('lecturer.materials.index', $course->id) }}"
                                           class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                            Manage Materials
                                        </a>

                                    @elseif($feature === 'assignments')

                                        <a href="{{ route('lecturer.assignments.index', $course->id) }}"
                                           class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                            Manage Assignments
                                        </a>

                                    @else

                                        <a href="{{ route('lecturer.progress.index', $course->id) }}"
                                           class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                                            View Student Progress
                                        </a>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>
    </div>
</x-app-layout>
