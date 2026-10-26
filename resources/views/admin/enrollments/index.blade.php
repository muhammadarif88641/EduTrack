
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            EduTrack - Enrollment Approval
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-block bg-gray-800 text-white px-4 py-2 rounded">
                    Back to Admin Dashboard
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

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

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-bold mb-4">
                    Manage Student Enrollment Requests
                </h3>

                <!-- Tabs -->
                <div class="flex flex-wrap gap-3 mb-6">

                    @foreach($tabs as $status => $items)
                        <a href="{{ route('admin.enrollments.index', ['tab' => $status]) }}"
                           class="px-4 py-2 rounded border
                           {{ $activeTab === $status
                                ? 'bg-gray-800 text-white'
                                : 'bg-white text-gray-800' }}">

                            {{ ucfirst($status) }}
                            ({{ $items->count() }})

                        </a>
                    @endforeach

                </div>

                @if($enrollments->isEmpty())

                    <p class="text-gray-600">
                        No {{ $activeTab }} enrollment requests found.
                    </p>

                @else

                    <div class="overflow-x-auto">

                        <table class="min-w-full border-collapse">

                            <thead>
                                <tr class="bg-gray-100 text-left">
                                    <th class="p-3 border">Student</th>
                                    <th class="p-3 border">Course Code</th>
                                    <th class="p-3 border">Course Name</th>
                                    <th class="p-3 border">Status</th>
                                    <th class="p-3 border">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($enrollments as $enrollment)

                                    <tr>
                                        <td class="p-3 border">
                                            {{ $enrollment->student?->name ?? 'Unknown Student' }}
                                        </td>

                                        <td class="p-3 border">
                                            {{ $enrollment->course?->course_code ?? 'N/A' }}
                                        </td>

                                        <td class="p-3 border">
                                            {{ $enrollment->course?->course_name ?? 'N/A' }}
                                        </td>

                                        <td class="p-3 border">
                                            {{ ucfirst($enrollment->status) }}
                                        </td>

                                        <td class="p-3 border">

                                            @if($enrollment->status === 'pending')

                                                <div class="flex flex-wrap gap-2">

                                                    <form method="POST"
                                                          action="{{ route('admin.enrollments.approve', $enrollment->id) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit"
                                                                class="bg-gray-800 text-white px-3 py-2 rounded">
                                                            Approve
                                                        </button>
                                                    </form>

                                                    <form method="POST"
                                                          action="{{ route('admin.enrollments.reject', $enrollment->id) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit"
                                                                class="border px-3 py-2 rounded">
                                                            Reject
                                                        </button>
                                                    </form>

                                                </div>

                                            @elseif($enrollment->status === 'rejected')

                                                <form method="POST"
                                                      action="{{ route('admin.enrollments.approve', $enrollment->id) }}">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="bg-gray-800 text-white px-3 py-2 rounded">
                                                        Approve Again
                                                    </button>
                                                </form>

                                            @else

                                                <span class="text-green-700 font-semibold">
                                                    Approved
                                                </span>

                                            @endif

                                        </td>
                                    </tr>

                                @endforeach
                            </tbody>

                        </table>

                    </div>

                @endif

            </div>
        </div>
    </div>
</x-app-layout>
