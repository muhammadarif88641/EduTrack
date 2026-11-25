<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Academic Reports
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        <div class="bg-slate-900 text-white rounded-xl p-6">

            <p class="text-sm text-blue-300 font-semibold">
                EduTrack Reporting System
            </p>

            <h1 class="text-3xl font-bold mt-2">
                Academic Reports
            </h1>

            <p class="text-slate-300 mt-2">

                @if($role === 'admin')

                    Review academic performance across all active courses.

                @else

                    Review academic performance across your own active courses.

                @endif

            </p>

        </div>

        <div class="bg-white border rounded-xl p-6 shadow-sm">

            <h2 class="text-lg font-bold text-gray-800 mb-4">
                Report Filters
            </h2>

            <form method="GET"
                  action="{{ route($role . '.reports.index') }}">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               value="{{ $filters['search'] }}"
                               placeholder="Name, email or course"
                               class="w-full border-gray-300 rounded-lg">

                    </div>

                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Course
                        </label>

                        <select name="course_id"
                                class="w-full border-gray-300 rounded-lg">

                            <option value="">
                                All Courses
                            </option>

                            @foreach($courses as $course)

                                <option value="{{ $course->id }}"
                                    @selected($filters['course_id'] == $course->id)>

                                    {{ $course->course_code }}
                                    - {{ $course->course_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Academic Risk
                        </label>

                        <select name="risk"
                                class="w-full border-gray-300 rounded-lg">

                            <option value="">
                                All Statuses
                            </option>

                            @foreach(['On Track', 'Needs Attention', 'At Risk', 'Insufficient Data'] as $status)

                                <option value="{{ $status }}"
                                    @selected($filters['risk'] === $status)>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Report Type
                        </label>

                        <select name="report"
                                class="w-full border-gray-300 rounded-lg">

                            <option value="summary"
                                @selected($filters['report'] === 'summary')>

                                Summary Report

                            </option>

                            <option value="details"
                                @selected($filters['report'] === 'details')>

                                Assignment Records

                            </option>

                        </select>

                    </div>

                </div>

                @if($errors->any())

                    <div class="bg-red-50 text-red-700 rounded-lg p-4 mt-4">

                        @foreach($errors->all() as $error)

                            <p>{{ $error }}</p>

                        @endforeach

                    </div>

                @endif

                <div class="flex flex-wrap gap-3 mt-5">

                    <button type="submit"
                            class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">

                        Apply Filters

                    </button>

                    <a href="{{ route($role . '.reports.index') }}"
                       class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-300">

                        Reset Filters

                    </a>

                </div>

            </form>

        </div>

        @php

            $cards = [
                ['Students', $stats['students'], 'text-blue-600'],
                ['Courses', $stats['courses'], 'text-blue-600'],
                ['Enrolments', $stats['enrollments'], 'text-gray-800'],
                ['Completed Assignments', $stats['completed'], 'text-green-600'],
                ['Outstanding Assignments', $stats['outstanding'], 'text-yellow-600'],
                ['Overdue Assignments', $stats['overdue'], 'text-red-600'],
                ['At-Risk Students', $stats['at_risk'], 'text-red-600'],
                ['Average Marks', $stats['average_marks'] !== null
                    ? $stats['average_marks'] . '%'
                    : 'N/A', 'text-blue-600'],
            ];

            $riskColors = [
                'On Track' => 'bg-green-100 text-green-700',
                'Needs Attention' => 'bg-yellow-100 text-yellow-700',
                'At Risk' => 'bg-red-100 text-red-700',
                'Insufficient Data' => 'bg-gray-100 text-gray-700',
            ];

            $submissionColors = [
                'Graded' => 'bg-green-100 text-green-700',
                'Submitted' => 'bg-blue-100 text-blue-700',
                'Overdue' => 'bg-red-100 text-red-700',
                'Not Submitted' => 'bg-yellow-100 text-yellow-700',
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

        <div class="bg-white border rounded-xl p-6 shadow-sm">

            <div class="flex justify-between items-center mb-3">

                <h2 class="font-bold text-gray-800">
                    Overall Assignment Completion
                </h2>

                <span class="font-bold text-blue-600">

                    {{ $stats['completion'] !== null
                        ? $stats['completion'] . '%'
                        : 'N/A' }}

                </span>

            </div>

            <progress
                value="{{ $stats['completion'] ?? 0 }}"
                max="100"
                aria-label="Overall assignment completion"
                class="w-full h-5 rounded-full overflow-hidden accent-green-500">
            </progress>

            <p class="text-sm text-gray-500 mt-3">
                Statistics are calculated using the currently selected filters.
            </p>

        </div>

        <div class="bg-white border rounded-xl shadow-sm overflow-hidden">

            <div class="p-6 border-b flex flex-wrap justify-between items-center gap-4">

                <div>

                    <h2 class="text-lg font-bold text-gray-800">

                        {{ $filters['report'] === 'summary'
                            ? 'Student Academic Summary'
                            : 'Individual Assignment Records' }}

                    </h2>

                    <p class="text-sm text-gray-500 mt-1">

                        {{ $filters['report'] === 'summary'
                            ? $rows->count()
                            : $details->count() }}

                        records found

                    </p>

                </div>

                <a href="{{ route($role . '.reports.export', $filters) }}"
                   class="bg-green-600 text-white px-5 py-3 rounded-lg hover:bg-green-700">

                    Download CSV

                </a>

            </div>

            <div class="overflow-x-auto">

                @if($filters['report'] === 'summary')

                    <table class="w-full text-sm text-left">

                        <thead class="bg-slate-900 text-white">

                            <tr>
                                <th class="p-4">No.</th>
                                <th class="p-4">Student</th>
                                <th class="p-4">Course</th>
                                <th class="p-4">Lecturer</th>
                                <th class="p-4">Total Tasks</th>
                                <th class="p-4">Completed</th>
                                <th class="p-4">Outstanding</th>
                                <th class="p-4">Overdue</th>
                                <th class="p-4">Completion</th>
                                <th class="p-4">Due Completion</th>
                                <th class="p-4">Marks</th>
                                <th class="p-4">Risk Status</th>
                            </tr>

                        </thead>

                        <tbody class="divide-y">

                            @forelse($rows as $row)

                                <tr class="hover:bg-gray-50">

                                    <td class="p-4">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="p-4">

                                        <p class="font-semibold text-gray-800">
                                            {{ $row->student_name }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            {{ $row->student_email }}
                                        </p>

                                    </td>

                                    <td class="p-4">

                                        <p class="font-semibold">
                                            {{ $row->course_code }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            {{ $row->course_name }}
                                        </p>

                                    </td>

                                    <td class="p-4">
                                        {{ $row->lecturer_name }}
                                    </td>

                                    <td class="p-4">
                                        {{ $row->total_assignments }}
                                    </td>

                                    <td class="p-4 text-green-600 font-semibold">
                                        {{ $row->completed_assignments }}
                                    </td>

                                    <td class="p-4 text-yellow-600">
                                        {{ $row->pending_assignments }}
                                    </td>

                                    <td class="p-4 text-red-600 font-semibold">
                                        {{ $row->overdue_assignments }}
                                    </td>

                                    <td class="p-4">

                                        {{ $row->total_assignments > 0
                                            ? $row->completion_percentage . '%'
                                            : 'N/A' }}

                                    </td>

                                    <td class="p-4">

                                        {{ $row->due_completion_percentage !== null
                                            ? $row->due_completion_percentage . '%'
                                            : 'N/A' }}

                                    </td>

                                    <td class="p-4 font-semibold">

                                        {{ $row->average_marks !== null
                                            ? $row->average_marks . '%'
                                            : 'N/A' }}

                                    </td>

                                    <td class="p-4">

                                        <span class="inline-block whitespace-nowrap px-3 py-1 rounded-full text-xs font-semibold {{ $riskColors[$row->risk_status] }}">

                                            {{ $row->risk_status }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="12"
                                        class="p-8 text-center text-gray-500">

                                        No academic records match your filters.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                @else

                    <table class="w-full text-sm text-left">

                        <thead class="bg-slate-900 text-white">

                            <tr>
                                <th class="p-4">No.</th>
                                <th class="p-4">Student</th>
                                <th class="p-4">Course</th>
                                <th class="p-4">Assignment</th>
                                <th class="p-4">Week</th>
                                <th class="p-4">Due Date</th>
                                <th class="p-4">Submitted At</th>
                                <th class="p-4">Marks</th>
                                <th class="p-4">Status</th>
                            </tr>

                        </thead>

                        <tbody class="divide-y">

                            @forelse($details as $row)

                                <tr class="hover:bg-gray-50">

                                    <td class="p-4">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="p-4">

                                        <p class="font-semibold">
                                            {{ $row->student_name }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            {{ $row->student_email }}
                                        </p>

                                    </td>

                                    <td class="p-4">

                                        <p class="font-semibold">
                                            {{ $row->course_code }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            {{ $row->course_name }}
                                        </p>

                                    </td>

                                    <td class="p-4">
                                        {{ $row->assignment_title }}
                                    </td>

                                    <td class="p-4">
                                        {{ $row->week_number }}
                                    </td>

                                    <td class="p-4 whitespace-nowrap">

                                        {{ $row->due_date
                                            ? \Carbon\Carbon::parse($row->due_date)->format('d M Y, h:i A')
                                            : 'No deadline' }}

                                    </td>

                                    <td class="p-4 whitespace-nowrap">

                                        {{ $row->submitted_at
                                            ? \Carbon\Carbon::parse($row->submitted_at)->format('d M Y, h:i A')
                                            : '-' }}

                                    </td>

                                    <td class="p-4 whitespace-nowrap font-semibold">

                                        {{ $row->marks_obtained !== null
                                            ? $row->marks_obtained . ' / ' . $row->max_marks
                                            : 'Not graded' }}

                                    </td>

                                    <td class="p-4">

                                        <span class="inline-block whitespace-nowrap px-3 py-1 rounded-full text-xs font-semibold {{ $submissionColors[$row->display_status] ?? 'bg-gray-100 text-gray-700' }}">

                                            {{ $row->display_status }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="9"
                                        class="p-8 text-center text-gray-500">

                                        No assignment records match your filters.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                @endif

            </div>

            <div class="p-4 bg-gray-50 border-t text-xs text-gray-500">

                Academic records are generated from active courses and approved
                enrolments. CSV downloads include the currently applied filters.

            </div>

        </div>

    </div>

</x-app-layout>