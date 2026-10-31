<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Student Progress - {{ $course->course_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('lecturer.courses.index') }}"
                   class="text-blue-600 hover:underline">
                    Back to My Courses
                </a>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-bold mb-4">
                    Student Assignment Progress
                </h3>

                <div class="mb-5 flex flex-wrap gap-5">
                    <span class="text-green-600 font-semibold">
                        ● Finish = Submitted
                    </span>

                    <span class="text-red-600 font-semibold">
                        ● Ongoing = Not Submitted
                    </span>
                </div>

                @if ($assignments->isEmpty())
                    <p class="text-gray-500">
                        No assignments have been created for this course.
                    </p>
                @elseif ($enrollments->isEmpty())
                    <p class="text-gray-500">
                        No approved students are enrolled in this course.
                    </p>
                @else

                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-3 text-left">
                                        Student Name
                                    </th>

                                    @foreach ($assignments as $assignment)
                                        <th class="border px-4 py-3 text-center">
                                            {{ $assignment->title }}
                                        </th>
                                    @endforeach

                                    <th class="border px-4 py-3 text-center">
                                        Progress
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($enrollments as $enrollment)

                                    @php
                                        $studentSubmissions = $submissions->get(
                                            $enrollment->student_id,
                                            collect()
                                        );

                                        $completed = $studentSubmissions->count();

                                        $total = $assignments->count();

                                        $percentage = $total > 0
                                            ? round(($completed / $total) * 100)
                                            : 0;
                                    @endphp

                                    <tr>
                                        <td class="border px-4 py-3 font-semibold">
                                            {{ $enrollment->student->name }}
                                        </td>

                                        @foreach ($assignments as $assignment)

                                            @php
                                                $submitted = $studentSubmissions
                                                    ->contains('assignment_id', $assignment->id);
                                            @endphp

                                            <td class="border px-4 py-3 text-center">
                                                @if ($submitted)
                                                    <span class="text-green-600 font-semibold">
                                                        ● Finish
                                                    </span>
                                                @else
                                                    <span class="text-red-600 font-semibold">
                                                        ● Ongoing
                                                    </span>
                                                @endif
                                            </td>

                                        @endforeach

                                        <td class="border px-4 py-3 text-center">
                                            <div class="font-bold mb-2">
                                                {{ $percentage }}%
                                            </div>

                                        <div class="w-28 bg-gray-200 rounded-full h-3 mx-auto overflow-hidden">
                                        <progress
                                            value="{{ $percentage }}"
                                            max="100"
                                            class="w-full h-3 accent-green-500">
                                        </progress>
                                    </div>

                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $completed }}/{{ $total }} completed
                                            </p>
                                        </td>
                                    </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @endif

            </div>
        </div>
    </div>
</x-app-layout>
