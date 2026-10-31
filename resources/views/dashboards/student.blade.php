
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            EduTrack - Student Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h1 class="text-2xl font-bold mb-4">
                    Welcome, {{ Auth::user()->name }}!
                </h1>

                <p class="text-gray-600 mb-6">
                    You are logged in as an EduTrack Student.
                </p>

                <h2 class="text-lg font-semibold mb-4">
                    My Learning
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <a href="{{ route('student.courses.index') }}"
                       class="block border rounded-lg p-5 hover:bg-blue-50 hover:border-blue-500 transition duration-200">

                        <h3 class="font-bold text-blue-700">
                            Find Class
                        </h3>

                        <p class="text-gray-600 mt-2">
                            Search for courses and request enrollment.
                        </p>

                        <span class="text-blue-600 text-sm font-semibold mt-3 inline-block">
                            Browse Available Courses &rarr;
                        </span>
                    </a>

                    <a href="{{ route('student.classes.index') }}"
                       class="block border rounded-lg p-5 hover:bg-blue-50 hover:border-blue-500 transition duration-200">

                        <h3 class="font-bold text-blue-700">
                            My Classes
                        </h3>

                        <p class="text-gray-600 mt-2">
                            View courses you have joined.
                        </p>

                        <span class="text-blue-600 text-sm font-semibold mt-3 inline-block">
                            View My Classes &rarr;
                        </span>
                    </a>

                    <a href="{{ route('student.assignments.overview') }}"
                       class="block border rounded-lg p-5 hover:bg-blue-50 hover:border-blue-500 transition duration-200">

                        <h3 class="font-bold text-blue-700">
                            My Assignments
                        </h3>

                        <p class="text-gray-600 mt-2">
                            View assignments, deadlines and submission status from all courses.
                        </p>

                        <span class="text-blue-600 text-sm font-semibold mt-3 inline-block">
                            View My Assignments &rarr;
                        </span>
                    </a>

                   <a href="{{ route('student.progress.index') }}"
                    class="block border rounded-lg p-5 hover:bg-blue-50 hover:border-blue-500 transition duration-200">

                        <h3 class="font-bold text-blue-700">
                            My Progress
                        </h3>

                        <p class="text-gray-600 mt-2">
                            Track completed assignments, marks and progress across all courses.
                        </p>

                        <span class="text-blue-600 text-sm font-semibold mt-3 inline-block">
                            View My Progress &rarr;
                        </span>

                    </a>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
