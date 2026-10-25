<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Course Approval Management
        </h2>
    </x-slot>

    @php
        $activeTab = request('tab', 'pending');

        if (!in_array($activeTab, ['pending', 'approved', 'rejected'])) {
            $activeTab = 'pending';
        }

        $tabs = [
            'pending' => [
                'label' => 'Pending',
                'count' => $pendingCourses->count(),
            ],
            'approved' => [
                'label' => 'Approved',
                'count' => $approvedCourses->count(),
            ],
            'rejected' => [
                'label' => 'Rejected',
                'count' => $rejectedCourses->count(),
            ],
        ];

        $courses = match ($activeTab) {
            'approved' => $approvedCourses,
            'rejected' => $rejectedCourses,
            default => $pendingCourses,
        };
    @endphp

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Back Button -->
            <a href="{{ route('admin.dashboard') }}"
               class="inline-block mb-6 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                &larr; Back to Admin Dashboard
            </a>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h3 class="text-2xl font-bold mb-2">
                    Manage Course Approvals
                </h3>

                <p class="text-gray-600 mb-6">
                    Review courses created by lecturers.
                    Approve or reject courses before students can enroll.
                </p>

                <!-- Course Status Tabs -->
                <div class="flex flex-wrap gap-3 mb-6 border-b pb-4">
                    @foreach ($tabs as $key => $tab)
                        <a href="{{ route('admin.courses.index', ['tab' => $key]) }}"
                           class="px-4 py-2 rounded-lg text-sm font-semibold transition
                           {{ $activeTab === $key
                               ? 'bg-gray-800 text-white'
                               : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">

                            {{ $tab['label'] }} ({{ $tab['count'] }})

                        </a>
                    @endforeach
                </div>

                <!-- Courses Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-3">Course Code</th>
                                <th class="p-3">Course Name</th>
                                <th class="p-3">Lecturer</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Action</th>
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
                                        {{ $course->lecturer?->name ?? 'Unknown' }}
                                    </td>

                                    <td class="p-3">
                                        @if ($course->status === 'approved')
                                            <span class="text-green-700 font-semibold">
                                                Approved
                                            </span>
                                        @elseif ($course->status === 'rejected')
                                            <span class="text-red-700 font-semibold">
                                                Rejected
                                            </span>
                                        @else
                                            <span class="text-yellow-700 font-semibold">
                                                Pending
                                            </span>
                                        @endif
                                    </td>

                                    <td class="p-3">
                                        <div class="flex flex-wrap gap-2">

                                            @if (in_array($course->status, ['pending', 'rejected']))
                                                <form method="POST"
                                                      action="{{ route('admin.courses.approve', $course) }}">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                        class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                                        {{ $course->status === 'rejected'
                                                            ? 'Approve Again'
                                                            : 'Approve' }}
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($course->status === 'pending')
                                                <form method="POST"
                                                      action="{{ route('admin.courses.reject', $course) }}">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                        onclick="return confirm('Reject this course?')"
                                                        class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                                        Reject
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($course->status === 'approved')
                                                <span class="text-gray-500">
                                                    No action needed
                                                </span>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="p-6 text-center text-gray-500">
                                        No {{ $activeTab }} courses found.
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
