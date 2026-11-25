<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Course
        </h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto space-y-6">

        <div class="bg-slate-900 text-white rounded-xl p-6">

            <p class="text-sm text-blue-300 font-semibold">
                Course Management
            </p>

            <h1 class="text-3xl font-bold mt-2">
                Edit Course
            </h1>

            <p class="text-slate-300 mt-2">
                Update your existing course information.
            </p>

        </div>

        <div class="bg-white border rounded-xl p-6 shadow-sm">

            <form method="POST"
                  action="{{ route('lecturer.courses.update', $course->id) }}">

                @csrf
                @method('PATCH')

                <div class="mb-5">

                    <label for="course_code"
                           class="block font-semibold text-gray-700 mb-2">
                        Course Code
                    </label>

                    <input type="text"
                           id="course_code"
                           name="course_code"
                           value="{{ old('course_code', $course->course_code) }}"
                           maxlength="50"
                           class="w-full border-gray-300 rounded-lg"
                           required>

                    @error('course_code')
                        <p class="text-sm text-red-600 mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div class="mb-5">

                    <label for="course_name"
                           class="block font-semibold text-gray-700 mb-2">
                        Course Name
                    </label>

                    <input type="text"
                           id="course_name"
                           name="course_name"
                           value="{{ old('course_name', $course->course_name) }}"
                           maxlength="255"
                           class="w-full border-gray-300 rounded-lg"
                           required>

                    @error('course_name')
                        <p class="text-sm text-red-600 mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div class="mb-5">

                    <label for="description"
                           class="block font-semibold text-gray-700 mb-2">
                        Course Description
                    </label>

                    <textarea id="description"
                              name="description"
                              rows="6"
                              class="w-full border-gray-300 rounded-lg">{{ old('description', $course->description) }}</textarea>

                    @error('description')
                        <p class="text-sm text-red-600 mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">

                    <p class="font-semibold text-blue-800">
                        Important Information
                    </p>

                    <p class="text-sm text-blue-700 mt-2">
                        Updating this course will not remove existing
                        students, learning materials, assignments,
                        submissions or academic marks.
                    </p>

                </div>

                <div class="flex flex-wrap justify-end gap-3">

                    <a href="{{ route('lecturer.courses.show', $course->id) }}"
                       class="bg-gray-200 text-gray-700 px-5 py-3 rounded-lg hover:bg-gray-300">

                        Cancel

                    </a>

                    <button type="submit"
                            class="bg-blue-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700">

                        Save Changes

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>