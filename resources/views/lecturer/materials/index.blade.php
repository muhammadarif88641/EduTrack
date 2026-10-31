
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            EduTrack - Learning Materials
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 flex flex-wrap gap-3">
                <a href="{{ route('lecturer.courses.index') }}"
                   class="inline-block bg-gray-800 text-white px-4 py-2 rounded">
                    Back to My Courses
                </a>

                @if($course->status === 'approved')
                    <a href="{{ route('lecturer.materials.create', $course->id) }}"
                       class="inline-block bg-gray-800 text-white px-4 py-2 rounded">
                        + Upload Material
                    </a>
                @endif
            </div>

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-xl font-bold text-gray-800">
                    {{ $course->course_name }}
                </h3>

                <p class="text-gray-600 mt-1">
                    Course Code: {{ $course->course_code }}
                </p>

                <p class="mt-2 text-sm text-gray-600">
                    Course Status: {{ ucfirst($course->status) }}
                </p>

                @if($course->status !== 'approved')
                    <p class="mt-4 text-yellow-700">
                        This course must be approved by Admin before
                        you can upload learning materials.
                    </p>
                @endif

                <h4 class="font-bold text-lg mt-8 mb-4">
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

                                <div class="p-4 space-y-4">

                                    @forelse($weeklyMaterials->get($week, collect()) as $material)

                                        <div class="border rounded-lg p-4">

                                            <h5 class="font-bold text-gray-800">
                                                {{ $material->title }}
                                            </h5>

                                            <p class="text-gray-600 mt-2">
                                                {{ $material->description ?: 'No description provided.' }}
                                            </p>

                                            <p class="text-sm text-gray-500 mt-3">
                                                File: {{ $material->original_filename }}
                                            </p>

                                            <p class="text-sm text-gray-500 mt-1">
                                                Uploaded:
                                                {{ $material->created_at->format('d M Y, h:i A') }}
                                            </p>

                                        </div>

                                    @empty

                                        <p class="text-gray-500">
                                            No materials for this week.
                                        </p>

                                    @endforelse

                                </div>

                            </details>

                        @endfor

                    </div>

                @endif

            </div>

        </div>
    </div>
</x-app-layout>
