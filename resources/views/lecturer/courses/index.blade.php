
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Lecturer - My Courses
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('lecturer.dashboard') }}"
                   class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                    &larr; Back to Lecturer Dashboard
                </a>

                <a href="{{ route('lecturer.courses.create') }}"
                class="inline-block px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition">
                    + Create New Course
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 shadow-sm rounded-lg">
                <h3 class="text-2xl font-bold mb-4">
                    My Courses
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-3">Course Code</th>
                                <th class="p-3">Course Name</th>
                                <th class="p-3">Description</th>
                                <th class="p-3">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($courses as $course)
                                <tr class="border-b">
                                    <td class="p-3">
                                        {{ $course->course_code }}
                                    </td>

                                    <td class="p-3">
                                        {{ $course->course_name }}
                                    </td>

                                    <td class="p-3">
                                        {{ $course->description ?? '-' }}
                                    </td>

                                    <td class="p-3">
                                        @if ($course->status === 'approved')
                                            <span class="font-semibold text-green-700">
                                                Approved
                                            </span>
                                        @elseif ($course->status === 'rejected')
                                            <span class="font-semibold text-red-700">
                                                Rejected
                                            </span>
                                        @else
                                            <span class="font-semibold text-yellow-700">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="p-6 text-center text-gray-500">
                                        No courses created yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
