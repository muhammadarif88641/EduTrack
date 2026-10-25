<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Available Courses
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Back to Student Dashboard -->
            <div class="mb-6">
                <a href="{{ route('student.dashboard') }}"
                   class="inline-block bg-gray-800 text-white px-4 py-2 rounded">
                    Back to Student Dashboard
                </a>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-bold mb-4">
                    Browse Approved Courses
                </h3>

                @if($courses->isEmpty())
                    <p class="text-gray-600">
                        No approved courses are available yet.
                    </p>
                @else

                    <div class="space-y-4">

                        @foreach($courses as $course)

                            @php
                                $enrollment = $enrollments->get($course->id);
                            @endphp

                            <div class="border rounded-lg p-5">

                                <h4 class="font-bold text-lg text-gray-800">
                                    {{ $course->course_name }}
                                </h4>

                                <p class="text-sm text-gray-600 mt-1">
                                    Course Code:
                                    {{ $course->course_code }}
                                </p>

                                <p class="text-gray-700 mt-3">
                                    {{ $course->description ?: 'No description available.' }}
                                </p>

                                <div class="mt-4">

                                    @if(!$enrollment)

                                        <form method="POST"
                                              action="{{ route('student.courses.enroll', $course->id) }}">
                                            @csrf

                                            <button type="submit"
                                                    class="bg-gray-800 text-white px-4 py-2 rounded">
                                                Request Enrollment
                                            </button>
                                        </form>

                                    @elseif($enrollment->status === 'pending')

                                        <span class="text-yellow-700 font-semibold">
                                            Enrollment Pending
                                        </span>

                                    @elseif($enrollment->status === 'approved')

                                        <span class="text-green-700 font-semibold">
                                            Enrolled
                                        </span>

                                    @elseif($enrollment->status === 'rejected')

                                        <span class="text-red-700 font-semibold">
                                            Enrollment Rejected
                                        </span>

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
