<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            EduTrack - Lecturer Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h1 class="text-2xl font-bold mb-4">
                    Welcome, {{ Auth::user()->name }}!
                </h1>

                <p class="text-gray-600 mb-6">
                    You are logged in as an EduTrack Lecturer.
                </p>

                <h2 class="text-lg font-semibold mb-4">
                    Lecturer Management
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <a href="{{ route('lecturer.courses.index') }}"
                       class="block border rounded-lg p-5 hover:bg-blue-50 hover:border-blue-500 transition">

                        <h3 class="font-bold text-blue-700 text-lg">
                            My Courses
                        </h3>

                        <p class="text-gray-600 mt-2">
                            Create and manage your courses.
                        </p>

                        <span class="text-blue-600 text-sm font-semibold mt-3 inline-block">
                            Manage Courses &rarr;
                        </span>
                    </a>

                    <a href="{{ route('lecturer.courses.select-feature', 'materials') }}"
                       class="block border rounded-lg p-5 hover:bg-blue-50 hover:border-blue-500 transition">

                        <h3 class="font-bold text-blue-700 text-lg">
                            Learning Materials
                        </h3>

                        <p class="text-gray-600 mt-2">
                            Upload and manage weekly learning materials.
                        </p>

                        <span class="text-blue-600 text-sm font-semibold mt-3 inline-block">
                            Manage Materials &rarr;
                        </span>
                    </a>

                    <a href="{{ route('lecturer.courses.select-feature', 'assignments') }}"
                       class="block border rounded-lg p-5 hover:bg-blue-50 hover:border-blue-500 transition">

                        <h3 class="font-bold text-blue-700 text-lg">
                            Assignments
                        </h3>

                        <p class="text-gray-600 mt-2">
                            Create assignments and set deadlines.
                        </p>

                        <span class="text-blue-600 text-sm font-semibold mt-3 inline-block">
                            Manage Assignments &rarr;
                        </span>
                    </a>

                    <a href="{{ route('lecturer.courses.select-feature', 'progress') }}"
                       class="block border rounded-lg p-5 hover:bg-blue-50 hover:border-blue-500 transition">

                        <h3 class="font-bold text-blue-700 text-lg">
                            Student Progress
                        </h3>

                        <p class="text-gray-600 mt-2">
                            Monitor student submissions and completion percentages.
                        </p>

                        <span class="text-blue-600 text-sm font-semibold mt-3 inline-block">
                            View Student Progress &rarr;
                        </span>
                    </a>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
