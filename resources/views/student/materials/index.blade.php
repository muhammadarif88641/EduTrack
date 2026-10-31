
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            EduTrack - Learning Materials
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('student.classes.index') }}"
                   class="inline-block bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Back to My Classes
                </a>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-2xl font-bold text-gray-800">
                    {{ $course->course_name }}
                </h3>

                <p class="text-gray-600 mt-2">
                    Course Code: {{ $course->course_code }}
                </p>

                <h4 class="text-xl font-bold mt-8 mb-4">
                    Weekly Learning Materials
                </h4>

                @if($materials->isEmpty())

                    <p class="text-gray-600">
                        No learning materials have been uploaded yet.
                    </p>

                @else

                    @php
                        $weeklyMaterials = $materials->groupBy('week_number');
                    @endphp

                    <div class="space-y-4">

                        @for($week = 1; $week <= 14; $week++)

                            <details class="border rounded-lg overflow-hidden"
                                     {{ $week == 1 ? 'open' : '' }}>

                                <summary class="bg-gray-100 p-4 font-bold cursor-pointer hover:bg-gray-200">
                                    Week {{ $week }}
                                    ({{ $weeklyMaterials->get($week, collect())->count() }} Materials)
                                </summary>

                                <div class="p-4">

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                        @forelse($weeklyMaterials->get($week, collect()) as $material)

                                            <div class="border rounded-lg p-5">

                                                <h5 class="font-bold text-lg text-gray-800">
                                                    {{ $material->title }}
                                                </h5>

                                                <p class="text-gray-600 mt-2">
                                                    {{ $material->description ?: 'No description provided.' }}
                                                </p>

                                                <p class="text-sm text-gray-500 mt-3">
                                                    File: {{ $material->original_filename }}
                                                </p>

                                                <p class="text-sm text-gray-500 mt-2">
                                                    Uploaded:
                                                    {{ $material->created_at->format('d M Y') }}
                                                </p>

                                                <div class="flex flex-wrap gap-3 mt-4">

                                                    <a href="{{ route('student.materials.view', $material->id) }}"
                                                       target="_blank"
                                                       rel="noopener noreferrer"
                                                       class="inline-block bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800">
                                                        View PDF
                                                    </a>

                                                    <a href="{{ route('student.materials.download', $material->id) }}"
                                                       class="inline-block bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                                                        Download PDF
                                                    </a>

                                                </div>

                                            </div>

                                        @empty

                                            <p class="text-gray-500">
                                                No materials for this week.
                                            </p>

                                        @endforelse

                                    </div>

                                </div>

                            </details>

                        @endfor

                    </div>

                @endif

            </div>

        </div>
    </div>
</x-app-layout>
