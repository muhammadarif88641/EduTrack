<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            My Courses
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        <div class="flex flex-wrap justify-between items-center gap-4">

            <div>

                <h1 class="text-2xl font-bold text-gray-800">
                    My Learning Courses
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Access your enrolled courses and learning activities.
                </p>

            </div>

            <a href="{{ route('student.courses.index') }}"
               class="bg-blue-600 text-white px-5 py-3 rounded-lg font-semibold hover:bg-blue-700">

                + Browse Courses

            </a>

        </div>

        @php
            $activeEnrollments = $enrollments->filter(function ($enrollment) {
                return $enrollment->course
                    && $enrollment->status === 'approved'
                    && $enrollment->course->status === 'approved';
            });
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div class="bg-white border rounded-xl p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    Active Courses
                </p>

                <h2 class="text-3xl font-bold text-blue-600 mt-2">
                    {{ $activeEnrollments->count() }}
                </h2>

                <p class="text-sm text-gray-500 mt-2">
                    Courses available for learning
                </p>

            </div>

            <a href="{{ route('student.progress.index') }}"
               class="block bg-white border rounded-xl p-5 shadow-sm hover:border-blue-500 transition">

                <p class="text-sm text-gray-500">
                    Academic Progress
                </p>

                <h2 class="text-xl font-bold text-green-600 mt-2">
                    View My Progress &rarr;
                </h2>

                <p class="text-sm text-gray-500 mt-2">
                    Monitor your assignment completion and marks
                </p>

            </a>

        </div>

        <div>

            <h2 class="text-xl font-bold text-gray-800">
                Enrolled Courses
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Select a course to access weekly learning content.
            </p>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5">

            @forelse ($activeEnrollments as $enrollment)

                @php
                    $course = $enrollment->course;
                @endphp

                <div class="bg-white border rounded-xl shadow-sm overflow-hidden hover:shadow-md transition flex flex-col">

                    <div class="bg-slate-900 text-white p-6">

                        <p class="text-sm font-semibold text-blue-300">
                            {{ $course->course_code }}
                        </p>

                        <h2 class="text-xl font-bold mt-2">
                            {{ $course->course_name }}
                        </h2>

                    </div>

                    <div class="p-5 flex-1">

                        <span class="inline-block bg-green-100 text-green-700 rounded-full px-3 py-1 text-xs font-semibold">
                            Enrollment Approved
                        </span>

                        <p class="text-sm text-gray-600 mt-4">

                            {{ \Illuminate\Support\Str::limit(
                                $course->description ?: 'No description available.',
                                150
                            ) }}

                        </p>

                        <div class="mt-5 border-t pt-4">

                            <p class="text-sm text-gray-500">
                                Course Lecturer
                            </p>

                            <p class="font-semibold text-gray-800 mt-1">
                                {{ $course->lecturer?->name ?? 'Not available' }}
                            </p>

                        </div>

                    </div>

                    <div class="border-t p-5">

                        <a href="{{ route('student.courses.show', $course->id) }}"
                           class="block text-center bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">

                            Open Course &rarr;

                        </a>

                    </div>

                </div>

            @empty

                <div class="col-span-full bg-white border rounded-xl p-10 text-center">

                    <h2 class="text-xl font-bold text-gray-800">
                        No Active Courses
                    </h2>

                    <p class="text-gray-500 mt-2">
                        Browse available courses and request enrollment to begin learning.
                    </p>

                    <a href="{{ route('student.courses.index') }}"
                       class="inline-block mt-5 bg-blue-600 text-white px-5 py-3 rounded-lg font-semibold hover:bg-blue-700">

                        Browse Available Courses

                    </a>

                </div>

            @endforelse

        </div>

    </div>

</x-app-layout>