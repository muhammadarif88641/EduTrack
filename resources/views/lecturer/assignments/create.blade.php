
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Assignment - {{ $course->course_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                <form method="POST"
                      action="{{ route('lecturer.assignments.store', $course) }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium mb-2">
                            Assignment Title
                        </label>

                        <input type="text"
                               name="title"
                               value="{{ old('title') }}"
                               required
                               class="w-full border-gray-300 rounded-lg">
                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">
                            Instructions
                        </label>

                        <textarea name="instructions"
                                  rows="5"
                                  required
                                  class="w-full border-gray-300 rounded-lg">{{ old('instructions') }}</textarea>
                        @error('instructions')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">
                            Maximum Marks
                        </label>

                        <input type="number"
                               name="max_marks"
                               min="1"
                               value="{{ old('max_marks') }}"
                               required
                               class="w-full border-gray-300 rounded-lg">
                        @error('max_marks')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">
                            Due Date (Optional)
                        </label>

                        <input type="datetime-local"
                               name="due_date"
                               value="{{ old('due_date') }}"
                               class="w-full border-gray-300 rounded-lg">
                        @error('due_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between items-center mt-6">
                        <a href="{{ route('lecturer.assignments.index', $course) }}"
                           class="text-gray-600 hover:underline">
                            Cancel
                        </a>

                        <button type="submit"
                                class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">
                            Create Assignment
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
