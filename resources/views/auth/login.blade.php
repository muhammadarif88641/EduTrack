<x-guest-layout>

    <div class="mb-8">
    <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">
        EduTrack
    </h1>

    <p class="text-base text-gray-500 mt-2">
        Sign in to your learning account
    </p>
</div>

    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Email" />

            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="Enter your email"
            />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Password" />

            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Enter your password"
            />

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2"
            />
        </div>

        <!-- Login Role -->
        <div class="mt-5">
            <p class="text-sm font-medium text-gray-700 mb-3">
                Login As
            </p>

            <div class="flex flex-wrap items-center gap-x-8 gap-y-3">

                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input
                        type="radio"
                        name="role"
                        value="student"
                        class="w-4 h-4 text-blue-600 border-gray-300"
                        {{ old('role', 'student') === 'student' ? 'checked' : '' }}
                    >
                    <span class="text-sm text-gray-700">
                        Student
                    </span>
                </label>

                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input
                        type="radio"
                        name="role"
                        value="lecturer"
                        class="w-4 h-4 text-blue-600 border-gray-300"
                        {{ old('role') === 'lecturer' ? 'checked' : '' }}
                    >
                    <span class="text-sm text-gray-700">
                        Lecturer
                    </span>
                </label>

                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input
                        type="radio"
                        name="role"
                        value="admin"
                        class="w-4 h-4 text-blue-600 border-gray-300"
                        {{ old('role') === 'admin' ? 'checked' : '' }}
                    >
                    <span class="text-sm text-gray-700">
                        Admin
                    </span>
                </label>

            </div>

            <x-input-error
                :messages="$errors->get('role')"
                class="mt-2"
            />
        </div>

        <!-- Remember Me -->
        <div class="mt-5">
            <label class="inline-flex items-center gap-2">
                <input
                    type="checkbox"
                    name="remember"
                    class="rounded border-gray-300 text-blue-600"
                >

                <span class="text-sm text-gray-600">
                    Remember me
                </span>
            </label>
        </div>

        <!-- Login Button -->
        <div class="flex justify-end mt-6">
            <x-primary-button class="px-6 py-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <!-- Bottom Links -->
        <div class="mt-6 pt-4 border-t border-gray-200
                    flex flex-wrap items-center justify-between gap-3">

            @if (Route::has('password.request'))
                <a
                    href="{{ route('password.request') }}"
                    class="text-sm text-gray-600 underline
                           hover:text-gray-900"
                >
                    Forgot your password?
                </a>
            @endif

            @if (Route::has('register'))
                <a
                    href="{{ route('register') }}"
                    class="text-sm text-blue-600 underline
                           hover:text-blue-800 font-medium"
                >
                    Create new account
                </a>
            @endif

        </div>

    </form>

</x-guest-layout>