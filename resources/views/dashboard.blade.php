<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}
    <div id="map" class="absolute w-full h-full z-0"></div>
    <div id="map-menu" class="absolute top-0 left-0 w-full py-4 z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <select id="mapSwitcher" class="select">
                        <option value="open_street_map">OpenStreetMap</option>
                        <option value="google_maps">Google Maps</option>
                    </select>
                    <label class="input">
                        <svg id="searchLocationBtn" class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                stroke="currentColor">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </g>
                        </svg>
                        <input type="text" id="searchBox" placeholder="Search for a location..." class="grow">
                    </label>
                    <button id="saveMarkerBtn" class="btn btn-primary">Save Location</button>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
