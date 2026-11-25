<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Archived Courses
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        <div class="flex flex-wrap justify-between items-center gap-4">

            <div>

                <h1 class="text-2xl font-bold text-gray-800">
                    Course Archive
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Manage and restore archived academic courses.
                </p>

            </div>

            <a href="{{ route('admin.courses.index') }}"
               class="bg-slate-800 text-white px-5 py-3 rounded-lg hover:bg-slate-700">

                &larr; Course Management

            </a>

        </div>

        @if (session('success'))

            <div class="bg-green-100 text-green-800 p-4 rounded-lg">
                {{ session('success') }}
            </div>

        @endif

        @if (session('error'))

            <div class="bg-red-100 text-red-800 p-4 rounded-lg">
                {{ session('error') }}
            </div>

        @endif

        <div class="bg-white border rounded-xl p-6 shadow-sm">

            <p class="text-sm text-gray-500">
                Total Archived Courses
            </p>

            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $courses->count() }}
            </h2>

            <p class="text-sm text-gray-500 mt-2">
                Historical courses preserved in the system.
            </p>

        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">

            <h2 class="font-bold text-blue-800">
                Academic Record Preservation
            </h2>

            <p class="text-sm text-blue-700 mt-2">
                Archived courses retain their learning materials,
                assignments, student enrolments, submissions,
                marks and lecturer feedback.
            </p>

            <p class="text-sm text-blue-700 mt-2">
                Restoring a course will return it to active status.
            </p>

        </div>

        <div class="bg-white border rounded-xl shadow-sm overflow-hidden">

            <div class="p-5 border-b">

                <h2 class="text-lg font-bold text-gray-800">
                    Archived Course Records
                </h2>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead class="bg-slate-900 text-white">

                        <tr>
                            <th class="p-4">No.</th>
                            <th class="p-4">Course Code</th>
                            <th class="p-4">Course Name</th>
                            <th class="p-4">Lecturer</th>
                            <th class="p-4">Last Updated</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Action</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-200">

                        @forelse ($courses as $course)

                            <tr class="hover:bg-gray-50">

                                <td class="p-4">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="p-4 font-semibold text-blue-600">
                                    {{ $course->course_code }}
                                </td>

                                <td class="p-4 font-semibold text-gray-800">
                                    {{ $course->course_name }}
                                </td>

                                <td class="p-4">
                                    {{ $course->lecturer?->name ?? 'Not available' }}
                                </td>

                                <td class="p-4">

                                    {{ $course->updated_at?->format('d M Y, h:i A') ?? '-' }}

                                </td>

                                <td class="p-4">

                                    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Archived
                                    </span>

                                </td>

                                <td class="p-4">

                                    <form method="POST"
                                          action="{{ route('admin.archive.restore', $course->id) }}"
                                          onsubmit="return confirm('Are you sure you want to restore this course?');">

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">

                                            Restore

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="p-12 text-center">

                                    <h2 class="text-lg font-semibold text-gray-800">
                                        No Archived Courses
                                    </h2>

                                    <p class="text-gray-500 mt-2">
                                        Archived courses will appear here.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-app-layout>