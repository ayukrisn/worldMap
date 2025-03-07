console.log("JS is called!")

// Define the map, markers, and selectedMarker
let map = L.map('map').setView([0, 0], 2); // Default map view
let markers = [];
let selectedMarker = null;


/***
 * LAYERS
 */
// Define tile layers
let layers = {
    "open_street_map": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }),
    "google_maps": L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '&copy; Google'
    })
};

// Set the map preferences from the database
document.addEventListener("DOMContentLoaded", function () {
    axios.get('/map-preferences')
        .then(response => {
            // set the map according to the response
            let mapType = response.data.map_type || 'open_street_map';
            layers[mapType].addTo(map);
            console.log("Axios for getting map preference is called!")

            // set the switch according to the response
            let mapSwitcher = document.getElementById("mapSwitcher");
            if (mapSwitcher) {
                mapSwitcher.value = mapType;
            }
        })
        .catch(error => {
            console.error("Error fetching map preference:", error);
        });
})

// Save user's map preference
function saveMapPreference(mapType) {
    console.log(mapType)
    axios.post('/map-preferences', { map_type: mapType }, { headers: { 'Content-Type': 'application/json' }, },)
        .then(response => {
            console.log(response.data.message);
        })
        .catch(error => {
            console.error('Error saving map preference:', error);
        });
}

// Handle map switcher and save the choice to database
document.getElementById('mapSwitcher').addEventListener('change', function (e) {
    let selectedLayer = e.target.value;
    map.eachLayer(function (layer) {
        map.removeLayer(layer);
    });
    saveMapPreference(selectedLayer);
    layers[selectedLayer].addTo(map);
});

/***
 * MARKERS
 */

// Fetch and display existing markers
function loadMarkers() {
    axios.get('/markers').then(response => {
        // get the markerlist html and clear it
        let markerList = document.getElementById("markerList");
        markerList.innerHTML = "";

        response.data.forEach(markerData => {
            // save the marker and then show it to the map
            let marker = L.marker([markerData.latitude, markerData.longitude])
                .addTo(map)
                .bindPopup(`<b>${markerData.title}</b><br>${markerData.description}
                            <br><button class="edit-button" data-id="${markerData.id}">Edit</button>
                            <button class="delete-button" data-id="${markerData.id}">Delete</button>`);
            markers.push({ id: markerData.id, marker: marker });

            // put each marker into the list
            let listItem = document.createElement("li");
            listItem.className = "p-2 border-b cursor-pointer hover:bg-gray-200";
            listItem.dataset.lat = markerData.latitude;
            listItem.dataset.lng = markerData.longitude;

            listItem.innerHTML = `<strong>${markerData.title}</strong>: ${markerData.description}`;
            markerList.appendChild(listItem);

             // Move the map when clicked
             listItem.addEventListener("click", function () {
                map.setView([this.dataset.lat, this.dataset.lng], 14);
                marker.openPopup();
            });
        });
    })
    .catch(error => {
        console.error("Error fetching markers:", error);
    });
};

// Load markers when the page loads
document.addEventListener("DOMContentLoaded", function () {
    loadMarkers();
});

// Add marker on click
map.on('click', function (e) {
    console.log("Map is clicked!")
    if (selectedMarker) {
        map.removeLayer(selectedMarker);
    }
    selectedMarker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
});

// Save marker when the page loads
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("saveMarkerBtn").addEventListener("click", saveMarker);
});

function saveMarker() {
    if (!selectedMarker) return alert('Click on the map to select a marker position.');

    let title = prompt('Enter marker title:');
    let description = prompt('Enter marker description:');

    axios.post('/markers', {
        latitude: selectedMarker.getLatLng().lat,
        longitude: selectedMarker.getLatLng().lng,
        title: title,
        description: description
    }).then(response => {
        alert('Marker saved!');
        loadMarkers();
    }).catch(error => {
        alert('Error saving marker.');
        console.log(error);
    });
}

// Listen to the pop up opening event from marker
map.on("popupopen", function (event) {
    let popup = event.popup; // Get the popup instance

    let editButton = popup._contentNode.querySelector(".edit-button");
    let deleteButton = popup._contentNode.querySelector(".delete-button");

    if (editButton) {
        editButton.addEventListener("click", function () {
            let id = this.dataset.id;
            editMarker(id);
        });
    }

    if (deleteButton) {
        deleteButton.addEventListener("click", function () {
            let id = this.dataset.id;
            deleteMarker(id);
        });
    }
});

// Edit marker
function editMarker(id) {
    let newTitle = prompt('Enter new title:');
    let newDescription = prompt('Enter new description:');

    axios.put(`/markers/${id}`, {
        title: newTitle,
        description: newDescription,        
    }).then(response => {
        alert('Marker updated!');
        location.reload();
    }).catch(error => {
        alert('Error updating marker.');
        console.log(error);
    });
}

// Delete marker
function deleteMarker(id) {
    if (!confirm('Are you sure you want to delete this marker?')) return;

    axios.delete(`/markers/${id}`)
        .then(response => {
            alert('Marker deleted!');
            location.reload();
        }).catch(error => {
            alert('Error deleting marker.');
            console.log(error);
        });
}

/***
 * SEARCH
 */
// Handles the click from search location
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("searchLocationBtn").addEventListener("click", searchLocation);
});

// Search location function
function searchLocation() {
    let searchQuery = document.getElementById('searchBox').value;
    if (!searchQuery) {
        alert('Please enter a location name.');
        return;
    }

    // Use OpenStreetMap Nominatim API to get location coordinates
    let apiUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}`;

    axios.get(apiUrl)
        .then(response => {
            if (response.data.length === 0) {
                alert('Location not found.');
                return;
            }

            let location = response.data[0]; // Take the first result
            let lat = location.lat;
            let lon = location.lon;

            // Move the map to the searched location
            map.setView([lat, lon], 13);

            // Remove existing marker if any
            if (selectedMarker) {
                map.removeLayer(selectedMarker);
            }

            // Add a new marker at the searched location
            selectedMarker = L.marker([lat, lon]).addTo(map)
                .bindPopup(`<b>${location.display_name}</b>`)
                .openPopup();
        })
        .catch(error => {
            console.error('Error searching location:', error);
            alert('Error searching location.');
        });
}
