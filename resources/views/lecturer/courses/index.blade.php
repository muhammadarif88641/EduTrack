<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            My Courses
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto">

        <div class="flex flex-wrap justify-between items-center gap-4 mb-6">

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Course Management
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Create and manage your teaching courses.
                </p>
            </div>

            <a href="{{ route('lecturer.courses.create') }}"
               class="bg-blue-600 text-white px-5 py-3 rounded-lg font-semibold hover:bg-blue-700">

                + Create New Course

            </a>

        </div>

        @if (session('success'))

            <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>

        @endif

        @if (session('error'))

            <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-6">
                {{ session('error') }}
            </div>

        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

            <div class="bg-white border rounded-xl p-5">

                <p class="text-sm text-gray-500">
                    Total Courses
                </p>

                <h2 class="text-3xl font-bold text-gray-800 mt-2">
                    {{ $courses->count() }}
                </h2>

            </div>

            <div class="bg-white border rounded-xl p-5">

                <p class="text-sm text-gray-500">
                    Approved Courses
                </p>

                <h2 class="text-3xl font-bold text-green-600 mt-2">
                    {{ $courses->where('status', 'approved')->count() }}
                </h2>

            </div>

            <div class="bg-white border rounded-xl p-5">

                <p class="text-sm text-gray-500">
                    Pending Approval
                </p>

                <h2 class="text-3xl font-bold text-yellow-600 mt-2">
                    {{ $courses->where('status', 'pending')->count() }}
                </h2>

            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5">

            @forelse ($courses as $course)

                <div class="bg-white rounded-xl border shadow-sm overflow-hidden hover:shadow-md transition flex flex-col">

                    <div class="bg-slate-900 text-white p-5">

                        <p class="text-blue-300 text-sm font-semibold">
                            {{ $course->course_code }}
                        </p>

                        <h2 class="text-xl font-bold mt-2">
                            {{ $course->course_name }}
                        </h2>

                    </div>

                    <div class="p-5 flex-1">

                        @php
                            $statusClass = match ($course->status) {
                                'approved' => 'bg-green-100 text-green-700',
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                'archived' => 'bg-gray-200 text-gray-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        @endphp

                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">

                            {{ ucfirst($course->status) }}

                        </span>

                        <p class="text-sm text-gray-600 mt-4">

                            {{ \Illuminate\Support\Str::limit(
                                $course->description ?? 'No description available.',
                                150
                            ) }}

                        </p>

                    </div>

                    <div class="border-t p-5">

                        @if ($course->status === 'approved')

                            <a href="{{ route('lecturer.courses.show', $course->id) }}"
                               class="block text-center bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">

                                Open Course &rarr;

                            </a>

                        @elseif ($course->status === 'pending')

                            <p class="text-sm text-yellow-700 text-center">
                                Waiting for Admin approval
                            </p>

                        @elseif ($course->status === 'rejected')

                            <p class="text-sm text-red-600 text-center">
                                Course was rejected
                            </p>

                        @elseif ($course->status === 'archived')

                            <p class="text-sm text-gray-600 text-center">
                                Archived Course
                            </p>

                        @else

                            <p class="text-sm text-gray-600 text-center">
                                Course unavailable
                            </p>

                        @endif

                    </div>

                </div>

            @empty

                <div class="col-span-full bg-white border rounded-xl p-10 text-center">

                    <h2 class="text-xl font-bold text-gray-800">
                        No courses yet
                    </h2>

                    <p class="text-gray-500 mt-2">
                        Create your first course to get started.
                    </p>

                    <a href="{{ route('lecturer.courses.create') }}"
                       class="inline-block mt-5 bg-blue-600 text-white px-5 py-3 rounded-lg">

                        Create Course

                    </a>

                </div>

            @endforelse

        </div>

    </div>

</x-app-layout>