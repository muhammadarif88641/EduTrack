
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            EduTrack - Learning Materials
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('student.classes.index') }}"
                   class="inline-block bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Back to My Classes
                </a>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">

                <!-- Course Information -->
                <h3 class="text-2xl font-bold text-gray-800">
                    {{ $course->course_name }}
                </h3>

                <p class="text-gray-600 mt-2">
                    Course Code: {{ $course->course_code }}
                </p>

                <h4 class="text-xl font-bold mt-8 mb-4">
                    Available Learning Materials
                </h4>

                <!-- Check Materials -->
                @if($materials->isEmpty())

                    <p class="text-gray-600">
                        No learning materials have been uploaded yet.
                    </p>

                @else

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        @foreach($materials as $material)

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

                               
                                <!-- View and Download PDF Buttons -->
                                <div class="flex flex-wrap gap-3 mt-4">

                                    <!-- View PDF in browser -->
                                    <a href="{{ route('student.materials.view', $material->id) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-block bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                                        View PDF
                                    </a>

                                    <!-- Download PDF to computer -->
                                    <a href="{{ route('student.materials.download', $material->id) }}"
                                    class="inline-block bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                                        Download PDF
                                    </a>

                                </div>


                            </div>

                        @endforeach

                    </div>

                @endif

            </div>
        </div>
    </div>
</x-app-layout>
