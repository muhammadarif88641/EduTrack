<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        <div class="bg-slate-900 text-white rounded-xl p-6">

            <p class="text-sm text-blue-300 font-semibold">
                EduTrack Administration
            </p>

            <h1 class="text-3xl font-bold mt-2">
                Welcome, {{ Auth::user()->name }}
            </h1>

            <p class="text-slate-300 mt-2">
                Monitor system activity, academic performance and student progress.
            </p>

        </div>

        @php
            $cards = [
                ['Total Students', $stats['students'], 'text-blue-600'],
                ['Total Lecturers', $stats['lecturers'], 'text-blue-600'],
                ['Active Courses', $stats['active_courses'], 'text-green-600'],
                ['Approved Enrolments', $stats['approved_enrollments'], 'text-green-600'],
                ['Pending Students', $stats['pending_students'], 'text-yellow-600'],
                ['Pending Courses', $stats['pending_courses'], 'text-yellow-600'],
                ['Pending Enrolments', $stats['pending_enrollments'], 'text-yellow-600'],
                ['At-Risk Students', $stats['at_risk_students'], 'text-red-600'],
            ];
        @endphp

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            @foreach($cards as $card)

                <div class="bg-white border rounded-xl p-5 shadow-sm">

                    <p class="text-sm text-gray-500">
                        {{ $card[0] }}
                    </p>

                    <h2 class="text-3xl font-bold mt-2 {{ $card[2] }}">
                        {{ $card[1] }}
                    </h2>

                </div>

            @endforeach

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="bg-white border rounded-xl p-6 shadow-sm">

                <h2 class="text-lg font-bold text-gray-800">
                    Academic Risk Distribution
                </h2>

                <p class="text-sm text-gray-500 mt-1 mb-6">
                    Risk classification across active student-course enrolments.
                </p>

                @php
                    $riskTotal = array_sum($riskCounts);

                    $riskColors = [
                        'On Track' => 'accent-green-500',
                        'Needs Attention' => 'accent-yellow-500',
                        'At Risk' => 'accent-red-500',
                        'Insufficient Data' => 'accent-gray-400',
                    ];
                @endphp

                @if($riskTotal > 0)

                    @foreach($riskCounts as $status => $count)

                        <div class="mb-5">

                            <div class="flex justify-between text-sm mb-2">

                                <span>{{ $status }}</span>

                                <span class="font-semibold">
                                    {{ $count }}
                                    ({{ round($count / $riskTotal * 100, 1) }}%)
                                </span>

                            </div>

                            <progress
                                value="{{ $count }}"
                                max="{{ $riskTotal }}"
                                aria-label="{{ $status }}"
                                class="w-full h-4 rounded-full overflow-hidden {{ $riskColors[$status] }}">
                            </progress>

                        </div>

                    @endforeach

                @else

                    <p class="text-gray-500 py-8 text-center">
                        No academic records available.
                    </p>

                @endif

            </div>

            <div class="bg-white border rounded-xl p-6 shadow-sm">

                <h2 class="text-lg font-bold text-gray-800">
                    Assignment Submission Status
                </h2>

                <p class="text-sm text-gray-500 mt-1 mb-6">
                    Overall assignment completion across active courses.
                </p>

                @php
                    $submissionTotal = array_sum($submissionCounts);

                    $submissionColors = [
                        'Completed' => 'accent-green-500',
                        'Overdue' => 'accent-red-500',
                        'Outstanding' => 'accent-yellow-500',
                    ];
                @endphp

                @if($submissionTotal > 0)

                    @foreach($submissionCounts as $status => $count)

                        <div class="mb-5">

                            <div class="flex justify-between text-sm mb-2">

                                <span>{{ $status }}</span>

                                <span class="font-semibold">
                                    {{ $count }}
                                    ({{ round($count / $submissionTotal * 100, 1) }}%)
                                </span>

                            </div>

                            <progress
                                value="{{ $count }}"
                                max="{{ $submissionTotal }}"
                                aria-label="{{ $status }}"
                                class="w-full h-4 rounded-full overflow-hidden {{ $submissionColors[$status] }}">
                            </progress>

                        </div>

                    @endforeach

                @else

                    <p class="text-gray-500 py-8 text-center">
                        No assignment records available.
                    </p>

                @endif

            </div>

        </div>

        <div class="bg-white border rounded-xl p-6 shadow-sm">

            <h2 class="text-lg font-bold text-gray-800">
                Course Performance Comparison
            </h2>

            <p class="text-sm text-gray-500 mt-1 mb-6">
                Compare assignment completion and weighted academic marks.
            </p>

            @forelse($coursePerformance as $course)

                <div class="border-b last:border-b-0 pb-5 mb-5">

                    <h3 class="font-semibold text-gray-800">
                        {{ $course['course_code'] }}
                        - {{ $course['course_name'] }}
                    </h3>

                    <p class="text-xs text-gray-500 mt-1 mb-4">
                        {{ $course['students'] }} enrolled students
                    </p>

                    <div class="flex justify-between text-sm mb-2">

                        <span>Assignment Completion</span>

                        <span class="font-semibold text-blue-600">
                            {{ $course['completion'] !== null
                                ? $course['completion'] . '%'
                                : 'N/A' }}
                        </span>

                    </div>

                    <progress
                        value="{{ $course['completion'] ?? 0 }}"
                        max="100"
                        aria-label="Assignment completion"
                        class="w-full h-4 rounded-full overflow-hidden accent-blue-500">
                    </progress>

                    <div class="flex justify-between text-sm mt-4 mb-2">

                        <span>Weighted Academic Marks</span>

                        <span class="font-semibold text-green-600">
                            {{ $course['average_marks'] !== null
                                ? $course['average_marks'] . '%'
                                : 'N/A' }}
                        </span>

                    </div>

                    <progress
                        value="{{ $course['average_marks'] ?? 0 }}"
                        max="100"
                        aria-label="Weighted academic marks"
                        class="w-full h-4 rounded-full overflow-hidden accent-green-500">
                    </progress>

                </div>

            @empty

                <p class="text-gray-500 text-center py-8">
                    No course performance data available.
                </p>

            @endforelse

        </div>

        <div class="bg-white border rounded-xl shadow-sm overflow-hidden">

            <div class="p-6 border-b">

                <h2 class="text-lg font-bold text-gray-800">
                    Students Requiring Attention
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Academic risk monitoring for approved enrolments.
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead class="bg-slate-900 text-white">

                        <tr>
                            <th class="p-4">Student</th>
                            <th class="p-4">Course</th>
                            <th class="p-4">Completion</th>
                            <th class="p-4">Marks</th>
                            <th class="p-4">Overdue</th>
                            <th class="p-4">Risk Status</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        @forelse(
                            $rows->whereIn('risk_status', ['At Risk', 'Needs Attention'])
                                 ->sortByDesc('overdue_assignments')
                                 ->take(10) as $row
                        )

                            <tr class="hover:bg-gray-50">

                                <td class="p-4 font-semibold">
                                    {{ $row->student_name }}
                                </td>

                                <td class="p-4">
                                    {{ $row->course_name }}
                                </td>

                                <td class="p-4">
                                    {{ $row->due_completion_percentage !== null
                                        ? $row->due_completion_percentage . '%'
                                        : 'N/A' }}
                                </td>

                                <td class="p-4">
                                    {{ $row->average_marks !== null
                                        ? $row->average_marks . '%'
                                        : 'N/A' }}
                                </td>

                                <td class="p-4 text-red-600 font-semibold">
                                    {{ $row->overdue_assignments }}
                                </td>

                                <td class="p-4">

                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $row->risk_status === 'At Risk'
                                            ? 'bg-red-100 text-red-700'
                                            : 'bg-yellow-100 text-yellow-700' }}">

                                        {{ $row->risk_status }}

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="p-8 text-center text-gray-500">
                                    No students currently flagged for attention.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div>

            <h2 class="text-xl font-bold text-gray-800 mb-4">
                Quick Management
            </h2>

            @php
                $links = [
                    ['User Management', 'admin.users.index', 'Create and manage user accounts.'],
                    ['Student Approval', 'admin.students.index', 'Review new student registrations.'],
                    ['Course Management', 'admin.courses.index', 'Approve and manage courses.'],
                    ['Enrolment Management', 'admin.enrollments.index', 'Manage student enrolment requests.'],
                    ['Archived Courses', 'admin.archive.index', $stats['archived_courses'] . ' archived courses.'],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                @foreach($links as $link)

                    <a href="{{ route($link[1]) }}"
                       class="bg-white border rounded-xl p-5 shadow-sm hover:border-blue-500 hover:bg-blue-50 transition">

                        <h3 class="font-bold text-blue-600">
                            {{ $link[0] }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-2">
                            {{ $link[2] }}
                        </p>

                        <p class="text-sm font-semibold text-blue-600 mt-4">
                            Open &rarr;
                        </p>

                    </a>

                @endforeach

            </div>

        </div>

    </div>

</x-app-layout>