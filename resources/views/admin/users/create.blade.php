<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Create User
        </h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto space-y-6">

        <a href="{{ route('admin.users.index') }}"
           class="text-sm font-semibold text-blue-600 hover:text-blue-800">

            &larr; Back to User Management

        </a>

        <div class="bg-slate-900 text-white rounded-xl p-6">

            <h1 class="text-2xl font-bold">
                Create New User
            </h1>

            <p class="text-sm text-slate-300 mt-2">
                Register a new student or lecturer account.
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
                  action="{{ route('admin.users.store') }}"
                  class="space-y-6">

                @csrf

                <div>

                    <h2 class="text-lg font-bold text-gray-800">
                        Personal Information
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Enter the user's account details.
                    </p>

                </div>

                <div>

                    <label for="name"
                           class="block text-sm font-semibold text-gray-700 mb-2">

                        Full Name

                    </label>

                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Enter full name"
                           maxlength="255"
                           required
                           autocomplete="off"
                           class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">

                </div>

                <div>

                    <label for="email"
                           class="block text-sm font-semibold text-gray-700 mb-2">

                        Email Address

                    </label>

                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="Enter email address"
                           maxlength="255"
                           required
                           autocomplete="off"
                           class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">

                </div>

                <div class="border-t pt-6">

                    <h2 class="text-lg font-bold text-gray-800">
                        Account Role
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Select the type of account to create.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">

                        <label class="flex items-start gap-3 border rounded-xl p-5 cursor-pointer hover:bg-blue-50">

                            <input type="radio"
                                   name="role"
                                   value="student"
                                   @checked(old('role', 'student') === 'student')
                                   required
                                   class="mt-1 text-blue-600 focus:ring-blue-500">

                            <div>

                                <p class="font-bold text-gray-800">
                                    Student
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    Access courses, learning materials,
                                    assignments and academic progress.
                                </p>

                            </div>

                        </label>

                        <label class="flex items-start gap-3 border rounded-xl p-5 cursor-pointer hover:bg-blue-50">

                            <input type="radio"
                                   name="role"
                                   value="lecturer"
                                   @checked(old('role') === 'lecturer')
                                   required
                                   class="mt-1 text-blue-600 focus:ring-blue-500">

                            <div>

                                <p class="font-bold text-gray-800">
                                    Lecturer
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    Manage courses, learning materials,
                                    assignments and student progress.
                                </p>

                            </div>

                        </label>

                    </div>

                </div>

                <div class="border-t pt-6">

                    <h2 class="text-lg font-bold text-gray-800">
                        Account Security
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Set a strong password for the new account.
                    </p>

                </div>

                <div x-data="{ showPassword: false }">

                    <label for="password"
                           class="block text-sm font-semibold text-gray-700 mb-2">

                        Password

                    </label>

                    <div class="flex gap-2">

                        <input :type="showPassword ? 'text' : 'password'"
                               id="password"
                               name="password"
                               placeholder="Enter password"
                               minlength="8"
                               maxlength="72"
                               required
                               autocomplete="new-password"
                               class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">

                        <button type="button"
                                @click="showPassword = !showPassword"
                                class="bg-gray-100 border px-4 rounded-lg text-sm font-semibold text-gray-700">

                            <span x-text="showPassword ? 'Hide' : 'Show'"></span>

                        </button>

                    </div>

                    <p class="text-xs text-gray-500 mt-2">
                        Password must contain at least 8 characters.
                    </p>

                </div>

                <div>

                    <label for="password_confirmation"
                           class="block text-sm font-semibold text-gray-700 mb-2">

                        Confirm Password

                    </label>

                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Re-enter password"
                           minlength="8"
                           maxlength="72"
                           required
                           autocomplete="new-password"
                           class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">

                </div>

                <div class="bg-green-50 border border-green-200 rounded-xl p-5">

                    <h3 class="font-bold text-green-800">
                        Automatic Account Approval
                    </h3>

                    <p class="text-sm text-green-700 mt-2">
                        Accounts created by the administrator are
                        automatically approved. The new user can
                        log in immediately using the registered
                        email, password and selected role.
                    </p>

                </div>

                <div class="flex flex-wrap justify-end gap-3 border-t pt-6">

                    <a href="{{ route('admin.users.index') }}"
                       class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300">

                        Cancel

                    </a>

                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">

                        Create Account

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>