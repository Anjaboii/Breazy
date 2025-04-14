document.addEventListener("DOMContentLoaded", function () {
    // 🌍 Initialize the Leaflet Map inside the dashboard
    var map = L.map("map").setView([6.9271, 79.8612], 12); // Colombo

    // 🗺️ Add OpenStreetMap tiles
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    // 🔑 PurpleAir API Key
    const apiKey = "F1490A8B-0CA4-11F0-81BE-42010A80001F";
    const apiUrl = "https://api.purpleair.com/v1/sensors?fields=name,latitude,longitude,pm2.5";

    // 🗂️ Store markers by sensor ID
    const markerMap = {};

    // 🎨 Get color by AQI value
    function getColorByAQI(aqi) {
        return aqi < 50 ? "green" :
               aqi < 100 ? "yellow" :
               aqi < 150 ? "orange" :
               aqi < 200 ? "red" :
               "purple";
    }

    // 💾 Save and update marker
    window.saveDetails = function(sensorId) {
        const newLoc = document.getElementById(`loc-${sensorId}`).value;
        const newAqi = parseFloat(document.getElementById(`aqi-${sensorId}`).value);

        const marker = markerMap[sensorId];
        if (!marker) return;

        // Update color
        const newColor = getColorByAQI(newAqi);
        marker.setStyle({ color: newColor });

        // Update popup with new content (non-editable)
        const updatedPopup = `<b>${newLoc}</b><br><b>AQI:</b> ${newAqi}`;
        marker.bindPopup(updatedPopup).openPopup();
    };

    // 🎯 Fetch AQI data from PurpleAir
    fetch(apiUrl, { headers: { "X-API-Key": apiKey } })
        .then(response => {
            if (!response.ok) {
                throw new Error(`API Error: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data.sensors || data.sensors.length === 0) {
                throw new Error("No sensor data available.");
            }

            data.sensors.forEach(sensor => {
                let aqi = sensor["pm2.5"];
                let lat = sensor.latitude;
                let lon = sensor.longitude;
                let locationName = sensor.name || "Unknown Location";
                let sensorId = sensor.sensor_index;

                let color = getColorByAQI(aqi);

                // 📍 Add AQI markers to the map
                const marker = L.circleMarker([lat, lon], {
                    color: color,
                    radius: 3,
                    fillOpacity: 0.8
                }).addTo(map);

                const popupContent = `
                    <div>
                        <label><b>Location:</b></label><br/>
                        <input type="text" id="loc-${sensorId}" value="${locationName}" /><br/>
                        <label><b>AQI:</b></label><br/>
                        <input type="number" id="aqi-${sensorId}" value="${aqi}" /><br/>
                        <button onclick="saveDetails(${sensorId})">Save</button>
                    </div>
                `;

                marker.bindPopup(popupContent);
                markerMap[sensorId] = marker;
            });
        })
        .catch(error => console.error("Error fetching AQI data:", error));
});
