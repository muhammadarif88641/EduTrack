<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            User Management
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        <div class="flex flex-wrap justify-between items-center gap-4">

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Manage Users
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Create and manage student and lecturer accounts.
                </p>
            </div>

            <a href="{{ route('admin.users.create') }}"
               class="bg-blue-600 text-white px-5 py-3 rounded-lg font-semibold hover:bg-blue-700">

                + Create User

            </a>

        </div>

        @if (session('success'))

            <div class="bg-green-100 text-green-800 p-4 rounded-lg">
                {{ session('success') }}
            </div>

        @endif

        @if (session('error'))

            <div class="bg-red-100 text-red-800 p-4 rounded-lg">
                {{ session('error') }}
            </div>

        @endif

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="bg-white border rounded-xl p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    Total Students
                </p>

                <h2 class="text-3xl font-bold text-blue-600 mt-2">
                    {{ $totalStudents }}
                </h2>

            </div>

            <div class="bg-white border rounded-xl p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    Total Lecturers
                </p>

                <h2 class="text-3xl font-bold text-gray-800 mt-2">
                    {{ $totalLecturers }}
                </h2>

            </div>

            <div class="bg-white border rounded-xl p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    Approved Accounts
                </p>

                <h2 class="text-3xl font-bold text-green-600 mt-2">
                    {{ $totalApproved }}
                </h2>

            </div>

            <div class="bg-white border rounded-xl p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    Pending Accounts
                </p>

                <h2 class="text-3xl font-bold text-yellow-600 mt-2">
                    {{ $totalPending }}
                </h2>

            </div>

        </div>

        <div class="bg-white border rounded-xl p-5 shadow-sm">

            <form method="GET"
                  action="{{ route('admin.users.index') }}">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Search Users
                        </label>

                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Name or email..."
                               maxlength="255"
                               class="w-full border-gray-300 rounded-lg text-sm">

                    </div>

                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            User Role
                        </label>

                        <select name="role"
                                class="w-full border-gray-300 rounded-lg text-sm">

                            <option value="">All Roles</option>

                            <option value="student"
                                @selected($role === 'student')>
                                Student
                            </option>

                            <option value="lecturer"
                                @selected($role === 'lecturer')>
                                Lecturer
                            </option>

                        </select>

                    </div>

                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Account Status
                        </label>

                        <select name="status"
                                class="w-full border-gray-300 rounded-lg text-sm">

                            <option value="">All Statuses</option>

                            <option value="pending"
                                @selected($status === 'pending')>
                                Pending
                            </option>

                            <option value="approved"
                                @selected($status === 'approved')>
                                Approved
                            </option>

                            <option value="rejected"
                                @selected($status === 'rejected')>
                                Rejected
                            </option>

                        </select>

                    </div>

                    <div class="flex items-end gap-2">

                        <button type="submit"
                                class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">

                            Search

                        </button>

                        <a href="{{ route('admin.users.index') }}"
                           class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

            @if ($errors->any())

                <div class="mt-4 text-sm text-red-600">
                    {{ $errors->first() }}
                </div>

            @endif

        </div>

        <div class="bg-white border rounded-xl shadow-sm overflow-hidden">

            <div class="p-5 border-b flex flex-wrap justify-between items-center gap-2">

                <h2 class="text-lg font-bold text-gray-800">
                    Registered Users
                </h2>

                <p class="text-sm text-gray-500">
                    {{ $users->total() }} matching records
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead class="bg-slate-900 text-white">

                        <tr>
                            <th class="p-4">No.</th>
                            <th class="p-4">Name</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Role</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Registered Date</th>
                            <th class="p-4">Action</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-200">

                        @forelse ($users as $user)

                            <tr class="hover:bg-gray-50">

                                <td class="p-4">

                                    {{ $users->firstItem() + $loop->index }}

                                </td>

                                <td class="p-4 font-semibold text-gray-800">
                                    {{ $user->name }}
                                </td>

                                <td class="p-4 text-gray-600">
                                    {{ $user->email }}
                                </td>

                                <td class="p-4">

                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">

                                        {{ ucfirst($user->role) }}

                                    </span>

                                </td>

                                <td class="p-4">

                                    @php
                                        $statusClass = match ($user->account_status) {
                                            'approved' => 'bg-green-100 text-green-700',
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp

                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">

                                        {{ ucfirst($user->account_status) }}

                                    </span>

                                </td>

                                <td class="p-4 text-gray-600">

                                    {{ $user->created_at?->format('d M Y') ?? '-' }}

                                </td>

                                <td class="p-4">

                                    <div class="flex flex-wrap gap-2">

                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                           class="bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-semibold hover:bg-blue-700">

                                            Edit

                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.users.destroy', $user->id) }}"
                                              onsubmit="return confirm('Delete this account permanently? Accounts containing academic records cannot be deleted.');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="bg-red-600 text-white px-4 py-2 rounded-lg text-xs font-semibold hover:bg-red-700">

                                                Delete

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="p-12 text-center">

                                    <h2 class="text-lg font-semibold text-gray-800">
                                        No Users Found
                                    </h2>

                                    <p class="text-gray-500 mt-2">
                                        Try changing your search filters or create a new user.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="p-5 border-t">

                {{ $users->links() }}

            </div>

        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">

            <h2 class="font-semibold text-blue-800">
                Academic Record Protection
            </h2>

            <p class="text-sm text-blue-700 mt-2">
                Accounts containing courses, enrolments or submissions
                cannot be deleted. Change the account status to rejected
                to prevent future login while preserving academic records.
            </p>

        </div>

    </div>

</x-app-layout>