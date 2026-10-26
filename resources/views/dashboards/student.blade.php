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
                class="block border rounded-lg p-4 hover:bg-blue-50 hover:border-blue-500 transition duration-200 cursor-pointer">

                    <h3 class="font-bold text-blue-700">
                        Find Class
                    </h3>

                    <p class="text-gray-600">
                        Search for courses and request enrollment.
                    </p>

                    <span class="text-blue-600 text-sm font-semibold mt-2 inline-block">
                        Browse Available Courses →
                    </span>

                </a>


                    
                <a href="{{ route('student.classes.index') }}"
                class="block border rounded-lg p-4 hover:bg-blue-50 hover:border-blue-500 transition duration-200 cursor-pointer">

                    <h3 class="font-bold text-blue-700">
                        My Classes
                    </h3>

                    <p class="text-gray-600">
                        View courses you have joined.
                    </p>

                    <span class="text-blue-600 text-sm font-semibold mt-2 inline-block">
                        View My Classes →
                    </span>

                </a>

                    <div class="border rounded-lg p-4">
                        <h3 class="font-bold">My Assignments</h3>
                        <p>View tasks, deadlines and submissions.</p>
                    </div>

                    <div class="border rounded-lg p-4">
                        <h3 class="font-bold">My Progress</h3>
                        <p>Track your completed tasks and marks.</p>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>