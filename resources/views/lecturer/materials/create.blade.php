
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            EduTrack - Upload Learning Material
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('lecturer.materials.index', $course->id) }}"
                   class="inline-block bg-gray-800 text-white px-4 py-2 rounded">
                    Back to Materials
                </a>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-xl font-bold mb-2">
                    Upload Material
                </h3>

                <p class="text-gray-600 mb-6">
                    Course: {{ $course->course_name }}
                    ({{ $course->course_code }})
                </p>

                @if($errors->any())
                    <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('lecturer.materials.store', $course->id) }}"
                      enctype="multipart/form-data">

                    @csrf

                    <div class="mb-4">
                        <label for="title" class="block font-semibold mb-2">
                            Material Title
                        </label>

                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old('title') }}"
                               required
                               class="w-full border-gray-300 rounded">
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block font-semibold mb-2">
                            Description (Optional)
                        </label>

                        <textarea id="description"
                                  name="description"
                                  rows="4"
                                  class="w-full border-gray-300 rounded">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="week_number" class="block font-semibold mb-2">
                            Teaching Week
                        </label>

                        <select id="week_number"
                                name="week_number"
                                required
                                class="w-full border-gray-300 rounded">

                            @for($week = 1; $week <= 14; $week++)
                                <option value="{{ $week }}"
                                    {{ old('week_number', 1) == $week ? 'selected' : '' }}>
                                    Week {{ $week }}
                                </option>
                            @endfor

                        </select>

                        @error('week_number')
                            <p class="text-red-600 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="file" class="block font-semibold mb-2">
                            Upload PDF File
                        </label>

                        <input type="file"
                               id="file"
                               name="file"
                               accept=".pdf,application/pdf"
                               required
                               class="w-full border rounded p-2">

                        <p class="text-sm text-gray-500 mt-2">
                            PDF files only. Maximum size: 10 MB.
                        </p>
                    </div>

                    <button type="submit"
                            class="bg-gray-800 text-white px-5 py-2 rounded">
                        Upload Material
                    </button>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
