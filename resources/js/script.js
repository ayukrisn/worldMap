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

// Fetch and display existing markers
function loadMarkers() {
    axios.get('/markers').then(response => {
        response.data.forEach(markerData => {
            let marker = L.marker([markerData.latitude, markerData.longitude])
                .addTo(map)
                .bindPopup(`<b>${markerData.title}</b><br>${markerData.description}
                            <br><button onclick="editMarker(${markerData.id})">Edit</button>
                            <button onclick="deleteMarker(${markerData.id})">Delete</button>`);
            markers.push({ id: markerData.id, marker: marker });
        });
    });
}

loadMarkers();

// Add marker on click
map.on('click', function (e) {
    console.log("Map is clicked!")
    if (selectedMarker) {
        map.removeLayer(selectedMarker);
    }
    selectedMarker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
});

// Save marker
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
        location.reload();
    }).catch(error => {
        alert('Error saving marker.');
        console.log(error);
    });
}

// Edit marker
function editMarker(id) {
    let newTitle = prompt('Enter new title:');
    let newDescription = prompt('Enter new description:');

    axios.put(`/markers/${id}`, {
        title: newTitle,
        description: newDescription
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