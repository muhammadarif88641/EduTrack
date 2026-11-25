<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Course Management
        </h2>
    </x-slot>

    @php
        $tab = request('tab', 'pending');

        $courses = match ($tab) {
            'approved' => $approvedCourses,
            'rejected' => $rejectedCourses,
            default => $pendingCourses,
        };
    @endphp

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        <div class="flex flex-wrap justify-between items-center gap-4">

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Manage Courses
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Approve, reject and archive academic courses.
                </p>
            </div>

            <a href="{{ route('admin.archive.index') }}"
               class="bg-slate-800 text-white px-5 py-3 rounded-lg font-semibold hover:bg-slate-700">

                Archived Courses &rarr;

            </a>

        </div>

        @if (session('success'))

            <div class="bg-green-100 text-green-800 rounded-lg p-4">
                {{ session('success') }}
            </div>

        @endif

        @if (session('error'))

            <div class="bg-red-100 text-red-800 rounded-lg p-4">
                {{ session('error') }}
            </div>

        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <div class="bg-white border rounded-xl p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    Pending Courses
                </p>

                <h2 class="text-3xl font-bold text-yellow-600 mt-2">
                    {{ $pendingCourses->count() }}
                </h2>

            </div>

            <div class="bg-white border rounded-xl p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    Approved Courses
                </p>

                <h2 class="text-3xl font-bold text-green-600 mt-2">
                    {{ $approvedCourses->count() }}
                </h2>

            </div>

            <div class="bg-white border rounded-xl p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    Rejected Courses
                </p>

                <h2 class="text-3xl font-bold text-red-600 mt-2">
                    {{ $rejectedCourses->count() }}
                </h2>

            </div>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <div class="flex flex-wrap gap-3">

                @foreach ([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected'
                ] as $key => $label)

                    <a href="{{ route('admin.courses.index', ['tab' => $key]) }}"
                       class="px-5 py-2 rounded-lg text-sm font-semibold transition
                       {{ $tab === $key
                           ? 'bg-blue-600 text-white'
                           : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">

                        {{ $label }}

                    </a>

                @endforeach

            </div>

        </div>

        <div class="bg-white border rounded-xl shadow-sm overflow-hidden">

            <div class="p-5 border-b">

                <h2 class="text-lg font-bold text-gray-800">
                    {{ ucfirst($tab) }} Courses
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

                                <td class="p-4">

                                    <p class="font-semibold text-gray-800">
                                        {{ $course->course_name }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">

                                        {{ \Illuminate\Support\Str::limit(
                                            $course->description ?? '',
                                            80
                                        ) }}

                                    </p>

                                </td>

                                <td class="p-4">
                                    {{ $course->lecturer?->name ?? 'Not available' }}
                                </td>

                                <td class="p-4">

                                    @php
                                        $statusClass = match ($course->status) {
                                            'approved' => 'bg-green-100 text-green-700',
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp

                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">

                                        {{ ucfirst($course->status) }}

                                    </span>

                                </td>

                                <td class="p-4">

                                    <div class="flex flex-wrap gap-2">

                                        @if ($course->status === 'pending' || $course->status === 'rejected')

                                            <form method="POST"
                                                  action="{{ route('admin.courses.approve', $course->id) }}"
                                                  onsubmit="return confirm('Approve this course?');">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">

                                                    Approve

                                                </button>

                                            </form>

                                        @endif

                                        @if ($course->status === 'pending')

                                            <form method="POST"
                                                  action="{{ route('admin.courses.reject', $course->id) }}"
                                                  onsubmit="return confirm('Reject this course?');">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">

                                                    Reject

                                                </button>

                                            </form>

                                        @endif

                                        @if ($course->status === 'approved')

                                            <form method="POST"
                                                  action="{{ route('admin.archive.store', $course->id) }}"
                                                  onsubmit="return confirm('Archive this course? All academic records will be preserved, but further modifications will be blocked until the course is restored.');">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                        class="bg-slate-800 text-white px-4 py-2 rounded-lg hover:bg-slate-700">

                                                    Archive Course

                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="p-12 text-center">

                                    <h2 class="text-lg font-semibold text-gray-800">
                                        No Courses Found
                                    </h2>

                                    <p class="text-gray-500 mt-2">
                                        There are no courses in this category.
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