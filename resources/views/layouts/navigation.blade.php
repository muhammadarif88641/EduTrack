
<nav class="bg-white border-b border-gray-200">

    <div class="flex items-center justify-between px-4 sm:px-6 py-4">

        <div class="flex items-center gap-4">

            <button
                type="button"
                @click="sidebarOpen = !sidebarOpen"
                :aria-expanded="sidebarOpen.toString()"
                aria-label="Toggle navigation"
                class="md:hidden p-2 rounded-lg hover:bg-gray-100">

                <svg class="w-6 h-6 text-gray-700"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>

                </svg>

            </button>

            <div>

                <h1 class="text-lg font-bold text-slate-800">
                    EduTrack Portal
                </h1>

                <p class="text-xs text-gray-500">
                    {{ ucfirst(Auth::user()->role) }} Workspace
                </p>

            </div>

        </div>

        <x-dropdown align="right" width="48">

            <x-slot name="trigger">

                <button type="button"
                        class="flex items-center gap-3 px-3 py-2
                               rounded-lg hover:bg-gray-100">

                    <div class="w-10 h-10 bg-blue-100
                                text-blue-700 rounded-full
                                flex items-center justify-center font-bold">

                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                    </div>

                    <div class="hidden sm:block text-left">

                        <p class="text-sm font-semibold text-gray-800">
                            {{ Auth::user()->name }}
                        </p>

                        <p class="text-xs text-gray-500">
                            {{ ucfirst(Auth::user()->role) }}
                        </p>

                    </div>

                    <svg class="w-4 h-4 text-gray-500"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M19 9l-7 7-7-7"/>

                    </svg>

                </button>

            </x-slot>

            <x-slot name="content">

                <x-dropdown-link :href="route('profile.edit')">
                    My Profile
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <x-dropdown-link
                        :href="route('logout')"
                        onclick="event.preventDefault();
                                 this.closest('form').submit();">

                        Log Out

                    </x-dropdown-link>

                </form>

            </x-slot>

        </x-dropdown>

    </div>

</nav>
