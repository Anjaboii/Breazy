// Initialize the Leaflet map
const map = L.map('map').setView([51.505, -0.09], 5); // Default center

// Load OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Your PurpleAir API Key (Replace with your real key)
const API_KEY = "6EF56E2D-0E56-11F0-81BE-42010A80001F"; 

// Fetch AQI data from PurpleAir API
async function fetchAQIData() {
    const url = "https://api.purpleair.com/v1/sensors?fields=latitude,longitude,pm2.5";
    
    const response = await fetch(url, {
        headers: { "X-API-Key": API_KEY }
    });

    const data = await response.json();
    console.log("AQI Data:", data);

    // Loop through sensors and add markers
    data.data.forEach(sensor => {
        const [id, lat, lon, pm25] = sensor;
        L.marker([lat, lon])
            .addTo(map)
            .bindPopup(`AQI: ${pm25} µg/m³`);
    });
}

// Call function to fetch AQI data
fetchAQIData();
