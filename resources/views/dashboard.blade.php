<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <select id="mapSwitcher" class="p-2 border rounded-md">
                        <option value="open_street_map">OpenStreetMap</option>
                        <option value="google_maps">Google Maps</option>
                    </select>
                    <button id="saveMarkerBtn" class="bg-blue-500 text-white px-4 py-2 rounded">Save Marker</button>
                    <div class="mb-4">
                        <input type="text" id="searchBox" placeholder="Search for a location..."
                            class="p-2 border rounded-md w-full">
                        <button id="searchLocationBtn"
                            class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Search</button>
                    </div>

                    <div id="map" style="height: 500px;"></div>
                </div>
                <div>
                    <h2 class="text-lg text-white font-bold mt-4">Saved Markers</h2>
                    <ul id="markerList" class="mt-2 p-2 border rounded-md bg-gray-100"></ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
