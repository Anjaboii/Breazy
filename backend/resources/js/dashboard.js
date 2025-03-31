document.addEventListener("DOMContentLoaded", function () {
    // 🌍 Initialize the Leaflet Map inside the dashboard
    var map = L.map("map").setView([6.9271, 79.8612], 12); // Colombo

    // 🗺️ Add OpenStreetMap tiles
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    // 🔑 PurpleAir API Key
    const apiKey = "F1490A8B-0CA4-11F0-81BE-42010A80001F"; // Replace with your API Key
    const apiUrl = "https://api.purpleair.com/v1/sensors?fields=latitude,longitude,pm2.5";

    // 🎯 Fetch AQI data from PurpleAir
    fetch(apiUrl, { headers: { "X-API-Key": apiKey } })
        .then(response => response.json())
        .then(data => {
            data.sensors.forEach(sensor => {
                let aqi = sensor["pm2.5"];
                let lat = sensor.latitude;
                let lon = sensor.longitude;

                // 🎨 Define marker color based on AQI
                let color = aqi < 50 ? "green" :
                            aqi < 100 ? "yellow" :
                            aqi < 150 ? "orange" : "red";

                // 📍 Add AQI markers to the map
                L.circleMarker([lat, lon], {
                    color: color,
                    radius: 8,
                    fillOpacity: 0.8
                })
                .bindPopup(`<b>AQI:</b> ${aqi}<br><b>Location:</b> (${lat}, ${lon})`)
                .addTo(map);
            });
        })
        .catch(error => console.error("Error fetching AQI data:", error));
});
