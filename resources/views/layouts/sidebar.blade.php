<div x-data="{ open: false,
 showSavedMarkers: false, 
 isMapsPage: window.location.pathname.includes('dashboard'),
 isProfilePage: window.location.pathname.includes('profile') }" class="flex">
    <!-- Sidebar -->
    <div class="w-20 h-screen bg-gray-800 text-white transition-all duration-300 flex flex-col">
        {{-- <!-- Sidebar Toggle Button -->
        <button @click="open = !open" class="p-3 focus:outline-none">
            <svg :class="open ? 'rotate-180' : ''" class="w-6 h-6 transition-transform" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button> --}}

        <!-- Navigation Links -->
        <ul class="mt-4 space-y-2 flex-1">
            <li class="w-full">
                <a href="{{ route('dashboard') }}"
                    class="w-full flex flex-col items-center justify-center p-3 hover:bg-gray-700 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-1" :fill="isMapsPage ? 'currentColor' : 'none'" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                    </svg>

                    <span class="text-xs text-center">Maps</span>
                </a>
            </li>
            <li class="w-full" x-show="!isProfilePage">
                <button @click="showSavedMarkers = !showSavedMarkers"
                    class="w-full flex flex-col items-center justify-center p-3 hover:bg-gray-700 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" :fill="showSavedMarkers ? 'currentColor' : 'none'"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6 mb-1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
                    </svg>

                    <span class="text-xs text-center">{{ __('Saved Location') }}</span>
                </button>
            </li>
        </ul>

        <ul class="mt-auto space-y-2">
            <li class="w-full">
                <a href="{{ route('profile.edit') }}"
                    class="w-full flex flex-col items-center justify-center p-3 hover:bg-gray-700 rounded">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.121 17.804A9 9 0 0112 3a9 9 0 016.879 14.804"></path>
                    </svg>
                    <span class="text-xs text-center">{{ __('Profile') }}</span>
                </a>
            </li>
            <li class="w-full">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex flex-col items-center justify-center p-3 hover:bg-gray-700 rounded">
                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7"></path>
                        </svg>
                        <span class="text-xs text-center">{{ __('Log Out') }}</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Saved Markers Panel -->
    <div x-show="showSavedMarkers" x-transition:enter="transform transition-transform duration-300 ease-out"
        x-transition:leave="transform transition-transform duration-300 ease-in" x-cloak
        class="w-72 bg-gray-800 text-white h-screen p-4 shadow-md transition-all duration-300">
        <h2 class="text-lg font-bold">{{ __('Saved Location') }}</h2>
        <ul id="markerList" class="mt-3 space-y-2">
        </ul>
    </div>
</div>
