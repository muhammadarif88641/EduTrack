
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Create New Course
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('lecturer.courses.index') }}"
               class="inline-block mb-6 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                &larr; Back to My Courses
            </a>

            <div class="bg-white p-6 shadow-sm rounded-lg">

                <h3 class="text-2xl font-bold mb-2">
                    Course Information
                </h3>

                <p class="text-gray-600 mb-6">
                    Fill in the course details below.
                    Your course will be sent to the Admin for approval.
                </p>

                <form method="POST"
                      action="{{ route('lecturer.courses.store') }}">
                    @csrf

                    <!-- Course Code -->
                    <div class="mb-5">
                        <x-input-label for="course_code"
                                       value="Course Code" />

                        <x-text-input id="course_code"
                                      name="course_code"
                                      type="text"
                                      class="block mt-1 w-full"
                                      :value="old('course_code')"
                                      placeholder="Example: BITP1113"
                                      required />

                        <x-input-error :messages="$errors->get('course_code')"
                                       class="mt-2" />
                    </div>

                    <!-- Course Name -->
                    <div class="mb-5">
                        <x-input-label for="course_name"
                                       value="Course Name" />

                        <x-text-input id="course_name"
                                      name="course_name"
                                      type="text"
                                      class="block mt-1 w-full"
                                      :value="old('course_name')"
                                      placeholder="Example: Introduction to Programming"
                                      required />

                        <x-input-error :messages="$errors->get('course_name')"
                                       class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <x-input-label for="description"
                                       value="Description" />

                        <textarea id="description"
                                  name="description"
                                  rows="5"
                                  class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Describe what students will learn in this course...">{{ old('description') }}</textarea>

                        <x-input-error :messages="$errors->get('description')"
                                       class="mt-2" />
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button>
                            Create Course
                        </x-primary-button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>
