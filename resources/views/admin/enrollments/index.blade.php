<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Enrolment Management
        </h2>
    </x-slot>

    @php
        $activeTab = request('tab', 'pending');

        if (!in_array($activeTab, ['pending', 'approved', 'rejected'])) {
            $activeTab = 'pending';
        }

        $tabs = [
            'pending' => $pendingEnrollments,
            'approved' => $approvedEnrollments,
            'rejected' => $rejectedEnrollments,
        ];

        $enrollments = $tabs[$activeTab];
    @endphp

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        <div class="flex flex-wrap justify-between items-center gap-4">

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Manage Student Enrolments
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Approve, reject and manage course enrolment requests.
                </p>
            </div>

            <a href="{{ route('admin.dashboard') }}"
               class="bg-slate-800 text-white px-5 py-3 rounded-lg hover:bg-slate-700">

                &larr; Dashboard

            </a>

        </div>

        @if(session('success'))

            <div class="bg-green-100 text-green-800 p-4 rounded-lg">
                {{ session('success') }}
            </div>

        @endif

        @if(session('error'))

            <div class="bg-red-100 text-red-800 p-4 rounded-lg">
                {{ session('error') }}
            </div>

        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <div class="bg-white border rounded-xl p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    Pending Enrolments
                </p>

                <h2 class="text-3xl font-bold text-yellow-600 mt-2">
                    {{ $pendingEnrollments->count() }}
                </h2>

            </div>

            <div class="bg-white border rounded-xl p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    Approved Enrolments
                </p>

                <h2 class="text-3xl font-bold text-green-600 mt-2">
                    {{ $approvedEnrollments->count() }}
                </h2>

            </div>

            <div class="bg-white border rounded-xl p-5 shadow-sm">

                <p class="text-sm text-gray-500">
                    Rejected Enrolments
                </p>

                <h2 class="text-3xl font-bold text-red-600 mt-2">
                    {{ $rejectedEnrollments->count() }}
                </h2>

            </div>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <div class="flex flex-wrap gap-3">

                @foreach($tabs as $status => $items)

                    <a href="{{ route('admin.enrollments.index', ['tab' => $status]) }}"
                       class="px-5 py-2 rounded-lg text-sm font-semibold
                       {{ $activeTab === $status
                            ? 'bg-blue-600 text-white'
                            : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">

                        {{ ucfirst($status) }}
                        ({{ $items->count() }})

                    </a>

                @endforeach

            </div>

        </div>

        <div class="bg-white border rounded-xl shadow-sm overflow-hidden">

            <div class="p-5 border-b">

                <h2 class="text-lg font-bold text-gray-800">
                    {{ ucfirst($activeTab) }} Enrolment Requests
                </h2>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead class="bg-slate-900 text-white">

                        <tr>
                            <th class="p-4">No.</th>
                            <th class="p-4">Student</th>
                            <th class="p-4">Course Code</th>
                            <th class="p-4">Course Name</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Action</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-200">

                        @forelse($enrollments as $enrollment)

                            <tr class="hover:bg-gray-50">

                                <td class="p-4">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="p-4 font-semibold text-gray-800">

                                    {{ $enrollment->student?->name ?? 'Unknown Student' }}

                                </td>

                                <td class="p-4 font-semibold text-blue-600">

                                    {{ $enrollment->course?->course_code ?? 'N/A' }}

                                </td>

                                <td class="p-4">

                                    {{ $enrollment->course?->course_name ?? 'N/A' }}

                                </td>

                                <td class="p-4">

                                    @php
                                        $statusClass = match ($enrollment->status) {
                                            'approved' => 'bg-green-100 text-green-700',
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp

                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">

                                        {{ ucfirst($enrollment->status) }}

                                    </span>

                                </td>

                                <td class="p-4">

                                    <div class="flex flex-wrap gap-2">

                                        @if($enrollment->course?->status !== 'approved')

                                            <span class="text-gray-500 text-sm font-semibold">
                                                Inactive Course - Protected
                                            </span>

                                        @else

                                            @if($enrollment->status === 'pending')

                                                <form method="POST"
                                                      action="{{ route('admin.enrollments.approve', $enrollment->id) }}">

                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700">

                                                        Approve

                                                    </button>

                                                </form>

                                                <form method="POST"
                                                      action="{{ route('admin.enrollments.reject', $enrollment->id) }}">

                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="bg-gray-200 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-300">

                                                        Reject

                                                    </button>

                                                </form>

                                            @elseif($enrollment->status === 'rejected')

                                                <form method="POST"
                                                      action="{{ route('admin.enrollments.approve', $enrollment->id) }}">

                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700">

                                                        Approve Again

                                                    </button>

                                                </form>

                                            @else

                                                <span class="text-green-700 font-semibold">

                                                    Approved - Protected

                                                </span>

                                            @endif

                                            @if(in_array($enrollment->status, ['pending', 'rejected'], true))

                                                <form method="POST"
                                                      action="{{ route('admin.enrollments.destroy', $enrollment->id) }}"
                                                      onsubmit="return confirm('Permanently delete this enrolment request?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700">

                                                        Delete

                                                    </button>

                                                </form>

                                            @endif

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="p-12 text-center">

                                    <h2 class="text-lg font-semibold text-gray-800">
                                        No Enrolment Requests Found
                                    </h2>

                                    <p class="text-gray-500 mt-2">

                                        No {{ $activeTab }} enrolment requests available.

                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">

            <h2 class="font-semibold text-blue-800">
                Academic Record Protection
            </h2>

            <p class="text-sm text-blue-700 mt-2">
                Only pending or rejected enrolment requests without
                assignment submissions can be deleted.
                Approved enrolments and archived course records
                are protected.
            </p>

        </div>

    </div>

</x-app-layout>