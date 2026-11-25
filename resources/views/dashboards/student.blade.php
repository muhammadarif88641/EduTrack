<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Student Dashboard
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        <div class="bg-slate-900 text-white rounded-xl p-6">

            <p class="text-sm text-blue-300 font-semibold">
                EduTrack Student Workspace
            </p>

            <h1 class="text-3xl font-bold mt-2">
                Welcome, {{ Auth::user()->name }}
            </h1>

            <p class="text-slate-300 mt-2">
                Track your learning progress, assignments and academic performance.
            </p>

            <a href="{{ route('student.classes.index') }}"
               class="inline-block bg-blue-600 text-white px-5 py-3 rounded-lg mt-5 hover:bg-blue-700">

                Continue Learning

            </a>

        </div>

        @php
            $cards = [
                ['Enrolled Courses', $stats['courses'], 'text-blue-600'],
                ['Total Assignments', $stats['total_assignments'], 'text-gray-800'],
                ['Completed', $stats['completed_assignments'], 'text-green-600'],
                ['Outstanding', $stats['pending_assignments'], 'text-yellow-600'],
                ['Overdue', $stats['overdue_assignments'], 'text-red-600'],
                ['Overall Marks', $stats['average_marks'] !== null
                    ? $stats['average_marks'] . '%' : 'N/A', 'text-blue-600'],
            ];

            $riskColors = [
                'On Track' => 'bg-green-100 text-green-700',
                'Needs Attention' => 'bg-yellow-100 text-yellow-700',
                'At Risk' => 'bg-red-100 text-red-700',
                'Insufficient Data' => 'bg-gray-100 text-gray-700',
            ];
        @endphp

        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">

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
                    Overall Learning Progress
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Assignment completion across your enrolled courses.
                </p>

                @if($stats['total_assignments'] > 0)

                    <div class="text-center py-8">

                        <h2 class="text-5xl font-bold text-blue-600">
                            {{ $stats['completion_percentage'] }}%
                        </h2>

                        <p class="text-sm text-gray-500 mt-3">
                            Overall Completion
                        </p>

                    </div>

                    <progress
                        value="{{ $stats['completion_percentage'] }}"
                        max="100"
                        aria-label="Overall learning progress"
                        class="w-full h-5 rounded-full overflow-hidden accent-green-500">
                    </progress>

                    <p class="text-sm text-gray-600 mt-4">

                        You have submitted
                        {{ $stats['completed_assignments'] }}
                        out of
                        {{ $stats['total_assignments'] }}
                        assignments.

                    </p>

                @else

                    <p class="text-gray-500 text-center py-8">
                        No assignments available yet.
                    </p>

                @endif

            </div>

            <div class="bg-white border rounded-xl p-6 shadow-sm">

                <h2 class="text-lg font-bold text-gray-800">
                    Assignment Submission Status
                </h2>

                <p class="text-sm text-gray-500 mt-1 mb-6">
                    Monitor your submitted and outstanding assignments.
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
                View your individual academic performance by course.
            </p>

            @forelse($rows as $row)

                <div class="border rounded-xl p-5 mb-4">

                    <div class="flex flex-wrap justify-between items-center gap-3">

                        <div>

                            <p class="text-xs font-semibold text-blue-600">
                                {{ $row->course_code }}
                            </p>

                            <h3 class="text-lg font-bold text-gray-800 mt-1">
                                {{ $row->course_name }}
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Lecturer: {{ $row->lecturer_name }}
                            </p>

                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $riskColors[$row->risk_status] }}">

                            {{ $row->risk_status }}

                        </span>

                    </div>

                    <div class="grid grid-cols-3 gap-3 mt-5">

                        <div class="bg-green-50 rounded-lg p-3">

                            <p class="text-xs text-gray-500">
                                Completed
                            </p>

                            <p class="text-xl font-bold text-green-600">
                                {{ $row->completed_assignments }}
                            </p>

                        </div>

                        <div class="bg-yellow-50 rounded-lg p-3">

                            <p class="text-xs text-gray-500">
                                Outstanding
                            </p>

                            <p class="text-xl font-bold text-yellow-600">
                                {{ $row->pending_assignments }}
                            </p>

                        </div>

                        <div class="bg-red-50 rounded-lg p-3">

                            <p class="text-xs text-gray-500">
                                Overdue
                            </p>

                            <p class="text-xl font-bold text-red-600">
                                {{ $row->overdue_assignments }}
                            </p>

                        </div>

                    </div>

                    <div class="flex justify-between text-sm mt-5 mb-2">

                        <span>Assignment Completion</span>

                        <span class="font-semibold text-blue-600">
                            {{ $row->total_assignments > 0
                                ? $row->completion_percentage . '%'
                                : 'N/A' }}
                        </span>

                    </div>

                    <progress
                        value="{{ $row->completion_percentage }}"
                        max="100"
                        aria-label="Assignment completion"
                        class="w-full h-4 rounded-full overflow-hidden accent-blue-500">
                    </progress>

                    <div class="flex justify-between text-sm mt-4 mb-2">

                        <span>Weighted Academic Marks</span>

                        <span class="font-semibold text-green-600">

                            {{ $row->average_marks !== null
                                ? $row->average_marks . '%'
                                : 'Not graded yet' }}

                        </span>

                    </div>

                    <progress
                        value="{{ $row->average_marks ?? 0 }}"
                        max="100"
                        aria-label="Weighted academic marks"
                        class="w-full h-4 rounded-full overflow-hidden accent-green-500">
                    </progress>

                    <div class="bg-gray-50 border rounded-lg p-4 mt-5">

                        <p class="text-sm font-semibold text-gray-800">
                            Academic Recommendation
                        </p>

                        <p class="text-sm text-gray-600 mt-2">

                            @if($row->risk_status === 'At Risk')

                                Prioritise your outstanding assignments and contact
                                your lecturer for additional academic support.

                            @elseif($row->risk_status === 'Needs Attention')

                                Review your upcoming deadlines and improve
                                your assignment performance.

                            @elseif($row->risk_status === 'On Track')

                                Maintain your current learning progress and
                                continue completing assignments on time.

                            @else

                                Complete your upcoming assignments to build
                                your academic performance record.

                            @endif

                        </p>

                    </div>

                    <a href="{{ route('student.courses.show', $row->course_id) }}"
                       class="inline-block bg-blue-600 text-white px-5 py-2 rounded-lg mt-5 hover:bg-blue-700">

                        Open Course

                    </a>

                </div>

            @empty

                <div class="text-center py-8">

                    <p class="text-gray-500">
                        You have not enrolled in any active courses.
                    </p>

                    <a href="{{ route('student.courses.index') }}"
                       class="inline-block bg-blue-600 text-white px-5 py-3 rounded-lg mt-4">

                        Browse Courses

                    </a>

                </div>

            @endforelse

        </div>

        <div>

            <h2 class="text-xl font-bold text-gray-800 mb-4">
                Quick Access
            </h2>

            @php
                $links = [
                    ['Browse Courses', 'student.courses.index', 'Discover available courses.'],
                    ['My Courses', 'student.classes.index', 'Access your enrolled courses.'],
                    ['My Assignments', 'student.assignments.overview', 'Manage your assignment submissions.'],
                    ['My Progress', 'student.progress.index', 'Review your learning progress.'],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                @foreach($links as $link)

                    <a href="{{ route($link[1]) }}"
                       class="bg-white border rounded-xl p-5 shadow-sm hover:border-blue-500 hover:bg-blue-50">

                        <h3 class="font-bold text-blue-600">
                            {{ $link[0] }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-2">
                            {{ $link[2] }}
                        </p>

                    </a>

                @endforeach

            </div>

        </div>

    </div>

</x-app-layout>