<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Assignment
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
                Update existing assignment information.
            </p>

        </div>

        @if($hasSubmissions)

            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5">

                <h2 class="font-semibold text-yellow-800">
                    Academic Record Protection
                </h2>

                <p class="text-sm text-yellow-700 mt-2">
                    Students have already submitted this assignment.
                    Maximum marks cannot be changed to protect
                    existing academic records.
                </p>

            </div>

        @endif

        <div class="bg-white border rounded-xl shadow-sm p-6">

            @if($errors->any())

                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">

                    <p class="font-semibold text-red-700 mb-2">
                        Please correct the following errors:
                    </p>

                    <ul class="list-disc list-inside text-sm text-red-600">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form method="POST"
                  action="{{ route('lecturer.assignments.update', $assignment->id) }}"
                  class="space-y-6">

                @csrf
                @method('PATCH')

                <div>

                    <h2 class="text-lg font-bold text-gray-800">
                        Assignment Information
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Update the assignment details below.
                    </p>

                </div>

                <div>

                    <label for="title"
                           class="block text-sm font-semibold text-gray-700 mb-2">

                        Assignment Title

                    </label>

                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title', $assignment->title) }}"
                           maxlength="255"
                           required
                           class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">

                </div>

                <div>

                    <label for="instructions"
                           class="block text-sm font-semibold text-gray-700 mb-2">

                        Assignment Instructions

                    </label>

                    <textarea id="instructions"
                              name="instructions"
                              rows="6"
                              required
                              class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">{{ old('instructions', $assignment->instructions) }}</textarea>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>

                        <label for="week_number"
                               class="block text-sm font-semibold text-gray-700 mb-2">

                            Week Number

                        </label>

                        <select id="week_number"
                                name="week_number"
                                required
                                class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">

                            @for($week = 1; $week <= 52; $week++)

                                <option value="{{ $week }}"
                                    @selected(old('week_number', $assignment->week_number) == $week)>

                                    Week {{ $week }}

                                </option>

                            @endfor

                        </select>

                    </div>

                    <div>

                        <label for="max_marks"
                               class="block text-sm font-semibold text-gray-700 mb-2">

                            Maximum Marks

                        </label>

                        <input type="number"
                               id="max_marks"
                               name="max_marks"
                               value="{{ $hasSubmissions ? $assignment->max_marks : old('max_marks', $assignment->max_marks) }}"
                               min="1"
                               max="1000"
                               required
                               @readonly($hasSubmissions)
                               class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 {{ $hasSubmissions ? 'bg-gray-100 cursor-not-allowed' : '' }}">

                        @if($hasSubmissions)

                            <p class="text-xs text-yellow-700 mt-2">
                                Maximum marks are protected.
                            </p>

                        @endif

                    </div>

                </div>

                <div>

                    <label for="due_date"
                           class="block text-sm font-semibold text-gray-700 mb-2">

                        Submission Deadline

                    </label>

                    <input type="datetime-local"
                           id="due_date"
                           name="due_date"
                           value="{{ old('due_date', $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('Y-m-d\TH:i') : '') }}"
                           class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">

                    <p class="text-xs text-gray-500 mt-2">
                        Leave empty if the assignment has no deadline.
                    </p>

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