// Initialize map
const map = L.map('map').setView([6.9271, 79.8612], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

// Load and display sensors
fetch('../api/get_locations.php')
    .then(res => res.json())
    .then(data => {
        data.forEach(loc => {
            L.marker([loc.latitude, loc.longitude])
                .bindPopup(`<b>${loc.name}</b><br>PM2.5: ${loc.pm25 || 'N/A'}`)
                .addTo(map);
        });
    });

// Load readings every 5 minutes
function updateReadings() {
    fetch('../api/get_readings.php')
        .then(res => res.json())
        .then(data => {
            document.getElementById('readings-container').innerHTML = 
                data.map(r => `
                    <div class="reading">
                        <h3>${r.name}</h3>
                        <p>PM2.5: ${r.pm25} µg/m³</p>
                        <p>PM10: ${r.pm10} µg/m³</p>
                    </div>
                `).join('');
        });
}
setInterval(updateReadings, 300000);
updateReadings();