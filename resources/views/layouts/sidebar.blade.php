@php
    $role = Auth::user()->role;

    $menus = match ($role) {

        'admin' => [
            [
                'label' => 'Dashboard',
                'url' => route('admin.dashboard'),
                'active' => request()->routeIs('admin.dashboard'),
            ],
            [
                'label' => 'Student Approval',
                'url' => route('admin.students.index'),
                'active' => request()->routeIs('admin.students.*'),
            ],
            [
                'label' => 'Course Management',
                'url' => route('admin.courses.index'),
                'active' => request()->routeIs('admin.courses.*'),
            ],
            [
                'label' => 'Enrolments',
                'url' => route('admin.enrollments.index'),
                'active' => request()->routeIs('admin.enrollments.*'),
            ],
        ],

        'lecturer' => [
            [
                'label' => 'Dashboard',
                'url' => route('lecturer.dashboard'),
                'active' => request()->routeIs('lecturer.dashboard'),
            ],
            [
                'label' => 'My Courses',
                'url' => route('lecturer.courses.index'),
                'active' => request()->routeIs(
                    'lecturer.courses.index',
                    'lecturer.courses.create',
                    'lecturer.courses.show',
                    'lecturer.materials.*',
                    'lecturer.assignments.*',
                    'lecturer.submissions.*'
                ) || (
                    request()->routeIs('lecturer.courses.select-feature')
                    && in_array(
                        request()->route('feature'),
                        ['materials', 'assignments']
                    )
                ),
            ],
            [
                'label' => 'Student Progress',
                'url' => route(
                    'lecturer.courses.select-feature',
                    ['feature' => 'progress']
                ),
                'active' => request()->routeIs('lecturer.progress.*')
                    || (
                        request()->routeIs('lecturer.courses.select-feature')
                        && request()->route('feature') === 'progress'
                    ),
            ],
        ],

        'student' => [
            [
                'label' => 'Dashboard',
                'url' => route('student.dashboard'),
                'active' => request()->routeIs('student.dashboard'),
            ],
            [
                'label' => 'Browse Courses',
                'url' => route('student.courses.index'),
                'active' => request()->routeIs('student.courses.index'),
            ],
            [
                'label' => 'My Courses',
                'url' => route('student.classes.index'),
                'active' => request()->routeIs(
                    'student.classes.*',
                    'student.courses.show',
                    'student.materials.*',
                    'student.assignments.index'
                ),
            ],
            [
                'label' => 'My Assignments',
                'url' => route('student.assignments.overview'),
                'active' => request()->routeIs('student.assignments.overview'),
            ],
            [
                'label' => 'My Progress',
                'url' => route('student.progress.index'),
                'active' => request()->routeIs('student.progress.*'),
            ],
        ],

        default => [],
    };
@endphp

<aside
    :class="sidebarOpen ? 'block' : 'hidden'"
    class="md:block md:w-64 md:shrink-0 bg-slate-900 text-white">

    <div class="px-6 py-7 border-b border-slate-700">

        <a href="{{ route('dashboard') }}"
           class="text-3xl font-bold tracking-tight">

            Edu<span class="text-blue-400">Track</span>

        </a>

        <p class="text-xs text-slate-400 mt-2">
            Academic Management System
        </p>

    </div>

    <div class="px-4 py-6">

        <p class="px-3 mb-4 text-xs font-semibold uppercase tracking-wider text-slate-400">

            {{ ucfirst($role) }} Portal

        </p>

        <nav class="space-y-2">

            @foreach ($menus as $menu)

                <a href="{{ $menu['url'] }}"
                   @if ($menu['active']) aria-current="page" @endif
                   class="block px-4 py-3 rounded-lg text-sm font-medium transition
                   {{ $menu['active']
                       ? 'bg-blue-600 text-white shadow'
                       : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

                    {{ $menu['label'] }}

                </a>

            @endforeach

        </nav>

    </div>

    <div class="mx-4 mt-4 pt-5 border-t border-slate-700">

        <p class="text-xs text-slate-400 px-3">
            EduTrack v2.0
        </p>

    </div>

</aside>