const map = L.map('admin-map').setView([6.9271, 79.8612], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

// Add click handler for new locations
map.on('click', function(e) {
    document.querySelector('input[name="lat"]').value = e.latlng.lat;
    document.querySelector('input[name="lng"]').value = e.latlng.lng;
});

// Form submission
document.getElementById('location-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    fetch('api/save_location.php', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert('Location added successfully!');
            window.location.reload();
        }
    });
});

// Load existing sensors
fetch('../api/get_locations.php')
    .then(res => res.json())
    .then(data => {
        data.forEach(loc => {
            L.marker([loc.latitude, loc.longitude])
                .bindPopup(`<b>${loc.name}</b><br><button onclick="deleteSensor('${loc.id}')">Delete</button>`)
                .addTo(map);
        });
    });

function deleteSensor(id) {
    if(confirm('Delete this sensor?')) {
        fetch(`api/delete_sensor.php?id=${id}`)
            .then(() => window.location.reload());
    }
}