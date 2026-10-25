<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Admin - Student Approval
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 shadow-sm rounded-lg">

                <h1 class="text-2xl font-bold mb-4">
                    Pending Student Registrations
                </h1>

                @if(session('success'))
                    <div class="bg-green-100 text-green-800 p-3 mb-4 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if($students->isEmpty())
                    <p class="text-gray-600">
                        No pending student registrations.
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border">

                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border p-3 text-left">Name</th>
                                    <th class="border p-3 text-left">Email</th>
                                    <th class="border p-3 text-left">Status</th>
                                    <th class="border p-3 text-left">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td class="border p-3">
                                            {{ $student->name }}
                                        </td>

                                        <td class="border p-3">
                                            {{ $student->email }}
                                        </td>

                                        <td class="border p-3">
                                            {{ ucfirst($student->account_status) }}
                                        </td>

                                        <td class="border p-3">
                                            <div class="flex gap-2">

                                                <form method="POST"
                                                      action="{{ route('admin.students.approve', $student) }}">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                        class="bg-green-600 text-white px-3 py-2 rounded">
                                                        Approve
                                                    </button>
                                                </form>

                                                <form method="POST"
                                                      action="{{ route('admin.students.reject', $student) }}">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                        class="bg-red-600 text-white px-3 py-2 rounded"
                                                        onclick="return confirm('Reject this student?')">
                                                        Reject
                                                    </button>
                                                </form>

                                            </div>
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