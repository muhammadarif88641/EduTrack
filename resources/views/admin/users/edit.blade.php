<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit User
        </h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto space-y-6">

        <a href="{{ route('admin.users.index') }}"
           class="text-sm font-semibold text-blue-600 hover:text-blue-800">

            &larr; Back to User Management

        </a>

        <div class="bg-slate-900 text-white rounded-xl p-6">

            <p class="text-sm text-blue-300 font-semibold">
                {{ ucfirst($user->role) }} Account
            </p>

            <h1 class="text-2xl font-bold mt-2">
                {{ $user->name }}
            </h1>

            <p class="text-sm text-slate-300 mt-2">
                Update account information and access permissions.
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
                  action="{{ route('admin.users.update', $user->id) }}"
                  class="space-y-6">

                @csrf
                @method('PATCH')

                <div>

                    <h2 class="text-lg font-bold text-gray-800">
                        Personal Information
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Update the user's personal details.
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
                           value="{{ old('name', $user->name) }}"
                           maxlength="255"
                           required
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
                           value="{{ old('email', $user->email) }}"
                           maxlength="255"
                           required
                           class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">

                </div>

                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Account Role
                    </label>

                    <div class="bg-gray-100 border rounded-lg p-3">

                        <p class="font-semibold text-gray-700">
                            {{ ucfirst($user->role) }}
                        </p>

                    </div>

                    <p class="text-xs text-gray-500 mt-2">
                        Account roles cannot be changed after registration
                        to protect existing academic records.
                    </p>

                </div>

                <div class="border-t pt-6">

                    <h2 class="text-lg font-bold text-gray-800">
                        Account Status
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Control whether this user can access EduTrack.
                    </p>

                    <div class="space-y-3 mt-4">

                        <label class="flex items-start gap-3 bg-green-50 border border-green-200 rounded-lg p-4 cursor-pointer">

                            <input type="radio"
                                   name="account_status"
                                   value="approved"
                                   @checked(old('account_status', $user->account_status) === 'approved')
                                   required
                                   class="mt-1 text-green-600">

                            <div>

                                <p class="font-semibold text-green-800">
                                    Approved
                                </p>

                                <p class="text-sm text-green-700 mt-1">
                                    User can log in and access the system.
                                </p>

                            </div>

                        </label>

                        <label class="flex items-start gap-3 bg-yellow-50 border border-yellow-200 rounded-lg p-4 cursor-pointer">

                            <input type="radio"
                                   name="account_status"
                                   value="pending"
                                   @checked(old('account_status', $user->account_status) === 'pending')
                                   required
                                   class="mt-1 text-yellow-600">

                            <div>

                                <p class="font-semibold text-yellow-800">
                                    Pending
                                </p>

                                <p class="text-sm text-yellow-700 mt-1">
                                    User must wait for administrator approval.
                                </p>

                            </div>

                        </label>

                        <label class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-lg p-4 cursor-pointer">

                            <input type="radio"
                                   name="account_status"
                                   value="rejected"
                                   @checked(old('account_status', $user->account_status) === 'rejected')
                                   required
                                   class="mt-1 text-red-600">

                            <div>

                                <p class="font-semibold text-red-800">
                                    Rejected / Deactivated
                                </p>

                                <p class="text-sm text-red-700 mt-1">
                                    User cannot access the system.
                                    Existing academic records are preserved.
                                </p>

                            </div>

                        </label>

                    </div>

                </div>

                <div class="border-t pt-6">

                    <h2 class="text-lg font-bold text-gray-800">
                        Reset Password
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Leave both password fields empty to keep
                        the current password.
                    </p>

                </div>

                <div x-data="{ showPassword: false }">

                    <label for="password"
                           class="block text-sm font-semibold text-gray-700 mb-2">

                        New Password

                    </label>

                    <div class="flex gap-2">

                        <input type="password"
                               :type="showPassword ? 'text' : 'password'"
                               id="password"
                               name="password"
                               placeholder="Enter new password"
                               minlength="8"
                               maxlength="72"
                               autocomplete="new-password"
                               class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">

                        <button type="button"
                                @click="showPassword = !showPassword"
                                class="bg-gray-100 border px-4 rounded-lg text-sm font-semibold text-gray-700">

                            <span x-text="showPassword ? 'Hide' : 'Show'"></span>

                        </button>

                    </div>

                    <p class="text-xs text-gray-500 mt-2">
                        Minimum 8 characters.
                    </p>

                </div>

                <div>

                    <label for="password_confirmation"
                           class="block text-sm font-semibold text-gray-700 mb-2">

                        Confirm New Password

                    </label>

                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Re-enter new password"
                           minlength="8"
                           maxlength="72"
                           autocomplete="new-password"
                           class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">

                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">

                    <h3 class="font-semibold text-blue-800">
                        Academic Record Protection
                    </h3>

                    <p class="text-sm text-blue-700 mt-2">
                        Updating or deactivating this account will not
                        delete its courses, enrolments, assignments,
                        submissions or academic results.
                    </p>

                </div>

                <div class="flex flex-wrap justify-end gap-3 border-t pt-6">

                    <a href="{{ route('admin.users.index') }}"
                       class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300">

                        Cancel

                    </a>

                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700"
                            onclick="return confirm('Save changes to this user account?');">

                        Save Changes

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>