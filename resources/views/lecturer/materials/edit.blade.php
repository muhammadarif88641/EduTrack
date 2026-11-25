<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Learning Material
        </h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto space-y-6">

        <a href="{{ route('lecturer.courses.show', $course->id) }}"
           class="text-sm font-semibold text-blue-600 hover:text-blue-800">

            &larr; Back to Course Workspace

        </a>

        <div class="bg-slate-900 text-white rounded-xl p-6">

            <p class="text-sm text-blue-300 font-semibold">
                {{ $course->course_code }}
            </p>

            <h1 class="text-2xl font-bold mt-2">
                {{ $course->course_name }}
            </h1>

            <p class="text-sm text-slate-300 mt-2">
                Update existing learning material information.
            </p>

        </div>

        <div class="bg-white border rounded-xl shadow-sm p-6">

            @if ($errors->any())

                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">

                    <p class="font-semibold text-red-700 mb-2">
                        Please correct the following errors:
                    </p>

                    <ul class="list-disc list-inside text-sm text-red-600">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form method="POST"
                  action="{{ route('lecturer.materials.update', $material->id) }}"
                  class="space-y-6">

                @csrf
                @method('PATCH')

                <div>

                    <h2 class="text-lg font-bold text-gray-800">
                        Material Information
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Edit the learning material details below.
                    </p>

                </div>

                <div>

                    <label for="title"
                           class="block text-sm font-semibold text-gray-700 mb-2">

                        Material Title

                    </label>

                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title', $material->title) }}"
                           maxlength="255"
                           required
                           class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">

                </div>

                <div>

                    <label for="week_number"
                           class="block text-sm font-semibold text-gray-700 mb-2">

                        Week Number

                    </label>

                    <select id="week_number"
                            name="week_number"
                            required
                            class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">

                        @for ($week = 1; $week <= 52; $week++)

                            <option value="{{ $week }}"
                                @selected(old('week_number', $material->week_number) == $week)>

                                Week {{ $week }}

                            </option>

                        @endfor

                    </select>

                </div>

                <div>

                    <label for="description"
                           class="block text-sm font-semibold text-gray-700 mb-2">

                        Description

                    </label>

                    <textarea id="description"
                              name="description"
                              rows="5"
                              placeholder="Enter learning material description..."
                              class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">{{ old('description', $material->description) }}</textarea>

                </div>

                <div class="border-t pt-6">

                    <h2 class="text-lg font-bold text-gray-800">
                        Current Uploaded File
                    </h2>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-5 mt-4">

                        <p class="text-xs font-semibold text-blue-600 uppercase">
                            Existing Learning File
                        </p>

                        <p class="font-semibold text-gray-800 mt-2">

                            {{ $material->original_filename ?? 'Learning material file' }}

                        </p>

                        <p class="text-sm text-blue-700 mt-3">
                            The existing uploaded file will remain unchanged
                            when you update this learning material.
                        </p>

                    </div>

                </div>

                <div class="flex flex-wrap justify-end gap-3 border-t pt-6">

                    <a href="{{ route('lecturer.courses.show', $course->id) }}"
                       class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300">

                        Cancel

                    </a>

                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">

                        Save Changes

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>