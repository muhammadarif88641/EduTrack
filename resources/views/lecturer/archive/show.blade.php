<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Archived Course Workspace
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        <a href="{{ route('lecturer.archive.index') }}"
           class="text-sm font-semibold text-blue-600 hover:text-blue-800">

            &larr; Back to Archived Courses

        </a>

        <div class="bg-slate-900 text-white rounded-xl p-6">

            <p class="text-blue-300 text-sm font-semibold">
                {{ $course->course_code }}
            </p>

            <h1 class="text-3xl font-bold mt-2">
                {{ $course->course_name }}
            </h1>

            <p class="text-slate-300 mt-3">
                {{ $course->description ?? 'No description available.' }}
            </p>

            <span class="inline-block mt-4 bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">

                Archived - Read Only

            </span>

        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5">

            <h2 class="font-bold text-yellow-800">
                Historical Academic Records
            </h2>

            <p class="text-sm text-yellow-700 mt-2">
                This course has been archived. Academic records
                are available for reference only.
                Contact the administrator to restore this course.
            </p>

        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="bg-white border rounded-xl p-5">

                <p class="text-sm text-gray-500">
                    Enrolled Students
                </p>

                <h2 class="text-3xl font-bold text-blue-600 mt-2">
                    {{ $totalStudents }}
                </h2>

            </div>

            <div class="bg-white border rounded-xl p-5">

                <p class="text-sm text-gray-500">
                    Learning Materials
                </p>

                <h2 class="text-3xl font-bold text-gray-800 mt-2">
                    {{ $totalMaterials }}
                </h2>

            </div>

            <div class="bg-white border rounded-xl p-5">

                <p class="text-sm text-gray-500">
                    Assignments
                </p>

                <h2 class="text-3xl font-bold text-gray-800 mt-2">
                    {{ $totalAssignments }}
                </h2>

            </div>

            <div class="bg-white border rounded-xl p-5">

                <p class="text-sm text-gray-500">
                    Total Submissions
                </p>

                <h2 class="text-3xl font-bold text-green-600 mt-2">
                    {{ $totalSubmissions }}
                </h2>

            </div>

        </div>

        <div class="bg-white border rounded-xl p-6">

            <p class="text-sm text-gray-500">
                Average Graded Submission
            </p>

            <h2 class="text-3xl font-bold text-green-600 mt-2">

                {{ $averageMarks !== null ? $averageMarks . '%' : 'N/A' }}

            </h2>

            <p class="text-sm text-gray-500 mt-2">
                Average percentage across graded submissions.
            </p>

        </div>

        <details class="bg-white border rounded-xl shadow-sm" open>

            <summary class="cursor-pointer p-5 font-bold text-gray-800">

                Student Records ({{ $totalStudents }})

            </summary>

            <div class="border-t overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead class="bg-slate-800 text-white">

                        <tr>
                            <th class="p-4">No.</th>
                            <th class="p-4">Student Name</th>
                            <th class="p-4">Email</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        @forelse ($students as $student)

                            <tr class="hover:bg-gray-50">

                                <td class="p-4">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="p-4 font-semibold">
                                    {{ $student->name }}
                                </td>

                                <td class="p-4">
                                    {{ $student->email }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="p-6 text-center text-gray-500">
                                    No student records available.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </details>

        <details class="bg-white border rounded-xl shadow-sm">

            <summary class="cursor-pointer p-5 font-bold text-gray-800">

                Learning Materials ({{ $totalMaterials }})

            </summary>

            <div class="border-t p-5 space-y-4">

                @forelse ($materials as $material)

                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">

                        <p class="text-xs font-semibold text-blue-600">
                            Week {{ $material->week_number }}
                        </p>

                        <h3 class="font-bold text-gray-800 mt-2">
                            {{ $material->title }}
                        </h3>

                        <p class="text-sm text-gray-600 mt-2">
                            {{ $material->description ?? 'No description.' }}
                        </p>

                        <p class="text-sm text-gray-500 mt-2">

                            File: {{ $material->original_filename ?? 'Learning file' }}

                        </p>

                    </div>

                @empty

                    <p class="text-sm text-gray-500">
                        No learning materials available.
                    </p>

                @endforelse

            </div>

        </details>

        <details class="bg-white border rounded-xl shadow-sm">

            <summary class="cursor-pointer p-5 font-bold text-gray-800">

                Assignment Records ({{ $totalAssignments }})

            </summary>

            <div class="border-t p-5 space-y-4">

                @forelse ($assignments as $assignment)

                    <div class="border rounded-lg p-4">

                        <p class="text-xs font-semibold text-blue-600">
                            Week {{ $assignment->week_number }}
                        </p>

                        <h3 class="font-bold text-gray-800 mt-2">
                            {{ $assignment->title }}
                        </h3>

                        <p class="text-sm text-gray-600 mt-2 whitespace-pre-line">
                            {{ $assignment->instructions }}
                        </p>

                        <p class="text-sm text-gray-600 mt-3">
                            Maximum Marks: {{ $assignment->max_marks }}
                        </p>

                        <p class="text-sm text-gray-600 mt-2">

                            Deadline:

                            {{ $assignment->due_date
                                ? \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, h:i A')
                                : 'No deadline' }}

                        </p>

                    </div>

                @empty

                    <p class="text-sm text-gray-500">
                        No assignment records available.
                    </p>

                @endforelse

            </div>

        </details>

        <details class="bg-white border rounded-xl shadow-sm">

            <summary class="cursor-pointer p-5 font-bold text-gray-800">

                Submissions and Marks ({{ $totalSubmissions }})

            </summary>

            <div class="border-t overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead class="bg-slate-800 text-white">

                        <tr>
                            <th class="p-4">Student</th>
                            <th class="p-4">Assignment</th>
                            <th class="p-4">Submitted File</th>
                            <th class="p-4">Submitted At</th>
                            <th class="p-4">Marks</th>
                            <th class="p-4">Feedback</th>
                            <th class="p-4">Status</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        @forelse ($submissions as $submission)

                            <tr class="hover:bg-gray-50">

                                <td class="p-4 font-semibold">
                                    {{ $submission->student_name }}
                                </td>

                                <td class="p-4">
                                    {{ $submission->assignment_title }}
                                </td>

                                <td class="p-4">
                                    {{ $submission->original_filename }}
                                </td>

                                <td class="p-4">

                                    {{ $submission->submitted_at
                                        ? \Carbon\Carbon::parse($submission->submitted_at)->format('d M Y, h:i A')
                                        : '-' }}

                                </td>

                                <td class="p-4 font-semibold">

                                    @if ($submission->marks_obtained !== null)

                                        {{ $submission->marks_obtained }}
                                        / {{ $submission->max_marks }}

                                    @else

                                        -

                                    @endif

                                </td>

                                <td class="p-4">
                                    {{ $submission->feedback ?? 'No feedback' }}
                                </td>

                                <td class="p-4">

                                    @if ($submission->marks_obtained !== null)

                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            Graded
                                        </span>

                                    @else

                                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            Ungraded
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="p-6 text-center text-gray-500">
                                    No submission records available.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </details>

    </div>

</x-app-layout>