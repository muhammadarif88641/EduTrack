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
                    My Archived Courses
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Access your previous courses and historical academic records.
                </p>
            </div>

            <a href="{{ route('lecturer.courses.index') }}"
               class="bg-slate-800 text-white px-5 py-3 rounded-lg hover:bg-slate-700">

                &larr; My Courses

            </a>

        </div>

        <div class="bg-white border rounded-xl p-6 shadow-sm">

            <p class="text-sm text-gray-500">
                Total Archived Courses
            </p>

            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $courses->count() }}
            </h2>

            <p class="text-sm text-gray-500 mt-2">
                Previous teaching courses available for reference.
            </p>

        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">

            <h2 class="font-semibold text-blue-800">
                Read-Only Access
            </h2>

            <p class="text-sm text-blue-700 mt-2">
                Archived courses retain their student records,
                learning materials, assignments, submissions,
                marks and feedback.
            </p>

            <p class="text-sm text-blue-700 mt-2">
                Only the administrator can restore an archived course.
            </p>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5">

            @forelse ($courses as $course)

                <div class="bg-white border rounded-xl shadow-sm overflow-hidden flex flex-col">

                    <div class="bg-slate-800 text-white p-6">

                        <p class="text-sm font-semibold text-slate-300">
                            {{ $course->course_code }}
                        </p>

                        <h2 class="text-xl font-bold mt-2">
                            {{ $course->course_name }}
                        </h2>

                    </div>

                    <div class="p-5 flex-1">

                        <span class="inline-block bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                            Archived
                        </span>

                        <p class="text-sm text-gray-600 mt-4">

                            {{ \Illuminate\Support\Str::limit(
                                $course->description ?: 'No description available.',
                                150
                            ) }}

                        </p>

                        <div class="border-t mt-5 pt-4">

                            <p class="text-sm text-gray-500">
                                Last Updated
                            </p>

                            <p class="text-sm font-semibold text-gray-800 mt-1">

                                {{ $course->updated_at?->format('d M Y, h:i A') ?? '-' }}

                            </p>

                        </div>

                    </div>

                    <div class="border-t p-5">

                        <a href="{{ route('lecturer.archive.show', $course->id) }}"
                           class="block text-center bg-slate-800 text-white px-4 py-3 rounded-lg font-semibold hover:bg-slate-700">

                            View Archived Course &rarr;

                        </a>

                    </div>

                </div>

            @empty

                <div class="col-span-full bg-white border rounded-xl p-12 text-center">

                    <h2 class="text-xl font-bold text-gray-800">
                        No Archived Courses
                    </h2>

                    <p class="text-gray-500 mt-2">
                        Your previous courses will appear here
                        when they are archived by the administrator.
                    </p>

                    <a href="{{ route('lecturer.courses.index') }}"
                       class="inline-block mt-5 bg-blue-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700">

                        View Active Courses

                    </a>

                </div>

            @endforelse

        </div>

    </div>

</x-app-layout>