<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Lecturer Dashboard
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        <div class="bg-slate-900 text-white rounded-xl p-6">

            <p class="text-sm text-blue-300 font-semibold">
                EduTrack Lecturer Workspace
            </p>

            <h1 class="text-3xl font-bold mt-2">
                Welcome, {{ Auth::user()->name }}
            </h1>

            <p class="text-slate-300 mt-2">
                Manage your courses and monitor student academic performance.
            </p>

            <a href="{{ route('lecturer.courses.create') }}"
               class="inline-block bg-blue-600 text-white px-5 py-3 rounded-lg mt-5 hover:bg-blue-700">

                + Create Course

            </a>

        </div>

        @php
            $cards = [
                ['Total Courses', $stats['total_courses'], 'text-blue-600'],
                ['Active Courses', $stats['active_courses'], 'text-green-600'],
                ['Pending Courses', $stats['pending_courses'], 'text-yellow-600'],
                ['Enrolled Students', $stats['students'], 'text-blue-600'],
                ['Learning Materials', $stats['materials'], 'text-blue-600'],
                ['Assignments', $stats['assignments'], 'text-gray-800'],
                ['Awaiting Grading', $stats['ungraded_submissions'], 'text-yellow-600'],
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
                    Student Academic Risk
                </h2>

                <p class="text-sm text-gray-500 mt-1 mb-6">
                    Academic risk classification across your active course enrolments.
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

                    <p class="text-gray-500 text-center py-8">
                        No academic records available.
                    </p>

                @endif

            </div>

            <div class="bg-white border rounded-xl p-6 shadow-sm">

                <h2 class="text-lg font-bold text-gray-800">
                    Assignment Submission Status
                </h2>

                <p class="text-sm text-gray-500 mt-1 mb-6">
                    Monitor assignment completion across your courses.
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

                    <p class="text-gray-500 text-center py-8">
                        No assignment records available.
                    </p>

                @endif

            </div>

        </div>

        <div class="bg-white border rounded-xl p-6 shadow-sm">

            <h2 class="text-lg font-bold text-gray-800">
                My Course Performance
            </h2>

            <p class="text-sm text-gray-500 mt-1 mb-6">
                Compare student completion and weighted academic marks.
            </p>

            @forelse($coursePerformance as $course)

                <div class="border-b last:border-b-0 pb-5 mb-5">

                    <div class="flex flex-wrap justify-between items-center gap-3">

                        <div>

                            <h3 class="font-bold text-gray-800">
                                {{ $course['course_code'] }}
                                - {{ $course['course_name'] }}
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">

                                {{ $course['students'] }} students |

                                {{ $course['at_risk'] }} at risk

                            </p>

                        </div>

                        <a href="{{ route('lecturer.courses.show', $course['course_id']) }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">

                            Open Course

                        </a>

                    </div>

                    <div class="flex justify-between text-sm mt-5 mb-2">

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
                    Identify students who may require additional academic support.
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead class="bg-slate-900 text-white">

                        <tr>
                            <th class="p-4">Student</th>
                            <th class="p-4">Course</th>
                            <th class="p-4">Due Completion</th>
                            <th class="p-4">Marks</th>
                            <th class="p-4">Overdue</th>
                            <th class="p-4">Risk Status</th>
                            <th class="p-4">Action</th>
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
                                    {{ $row->course_code }}
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

                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $row->risk_status === 'At Risk'
                                            ? 'bg-red-100 text-red-700'
                                            : 'bg-yellow-100 text-yellow-700' }}">

                                        {{ $row->risk_status }}

                                    </span>

                                </td>

                                <td class="p-4">

                                    <a href="{{ route('lecturer.progress.index', $row->course_id) }}"
                                       class="text-blue-600 font-semibold hover:underline">

                                        View Progress

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="p-8 text-center text-gray-500">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                <a href="{{ route('lecturer.courses.index') }}"
                   class="bg-white border rounded-xl p-5 shadow-sm hover:border-blue-500 hover:bg-blue-50">

                    <h3 class="font-bold text-blue-600">
                        My Courses
                    </h3>

                    <p class="text-sm text-gray-500 mt-2">
                        Create and manage your courses.
                    </p>

                </a>

                <a href="{{ route('lecturer.courses.select-feature', 'materials') }}"
                   class="bg-white border rounded-xl p-5 shadow-sm hover:border-blue-500 hover:bg-blue-50">

                    <h3 class="font-bold text-blue-600">
                        Learning Materials
                    </h3>

                    <p class="text-sm text-gray-500 mt-2">
                        Manage weekly learning content.
                    </p>

                </a>

                <a href="{{ route('lecturer.courses.select-feature', 'assignments') }}"
                   class="bg-white border rounded-xl p-5 shadow-sm hover:border-blue-500 hover:bg-blue-50">

                    <h3 class="font-bold text-blue-600">
                        Assignments
                    </h3>

                    <p class="text-sm text-gray-500 mt-2">
                        Manage assignments and student submissions.
                    </p>

                </a>

                <a href="{{ route('lecturer.courses.select-feature', 'progress') }}"
                   class="bg-white border rounded-xl p-5 shadow-sm hover:border-blue-500 hover:bg-blue-50">

                    <h3 class="font-bold text-blue-600">
                        Student Progress
                    </h3>

                    <p class="text-sm text-gray-500 mt-2">
                        Monitor student academic performance.
                    </p>

                </a>

                <a href="{{ route('lecturer.archive.index') }}"
                   class="bg-white border rounded-xl p-5 shadow-sm hover:border-blue-500 hover:bg-blue-50">

                    <h3 class="font-bold text-blue-600">
                        Archived Courses
                    </h3>

                    <p class="text-sm text-gray-500 mt-2">
                        View {{ $stats['archived_courses'] }} archived courses
                        and historical academic records.
                    </p>

                </a>

            </div>

        </div>

    </div>

</x-app-layout>