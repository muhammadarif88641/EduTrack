<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            EduTrack - Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h1 class="text-2xl font-bold mb-4">
                    Welcome, {{ Auth::user()->name }}!
                </h1>

                <p class="text-gray-600 mb-6">
                    You are logged in as an EduTrack Administrator.
                </p>

                <h2 class="text-lg font-semibold mb-3">
                    Admin Management
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                   <a href="{{ route('admin.students.index') }}"
   class="block border rounded-lg p-4 hover:bg-blue-50 hover:border-blue-500 transition duration-200 cursor-pointer">

    <h3 class="font-bold text-blue-700">
        Student Approval
    </h3>

    <p class="text-gray-600">
        Approve or reject new student accounts.
    </p>

    <span class="text-blue-600 text-sm font-semibold mt-2 inline-block">
        Manage Students →
    </span>

</a>

                   
                        <a href="{{ route('admin.courses.index') }}"
                        class="block border rounded-lg p-4 hover:bg-blue-50 hover:border-blue-500 transition duration-200 cursor-pointer">

                            <h3 class="font-bold text-blue-700">
                                Course Approval
                            </h3>

                            <p class="text-gray-600">
                                Approve courses created by lecturers.
                            </p>

                            <span class="text-blue-600 text-sm font-semibold mt-2 inline-block">
                                Manage Courses →
                            </span>

                        </a>


                    <div class="border rounded-lg p-4">
                        <h3 class="font-bold">Enrollment Requests</h3>
                        <p>Manage student requests to join courses.</p>
                    </div>

                    <div class="border rounded-lg p-4">
                        <h3 class="font-bold">Master Reset</h3>
                        <p>Reset student progress while keeping course materials.</p>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>