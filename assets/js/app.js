document.addEventListener('DOMContentLoaded', function() {
    // Initialize the map
    const map = L.map('map').setView([6.9271, 79.8612], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add loading control
    const loadingControl = L.control({position: 'topright'});
    loadingControl.onAdd = function() {
        this._div = L.DomUtil.create('div', 'loading-control');
        this.update('Loading data...');
        return this._div;
    };
    loadingControl.update = function(text) {
        this._div.innerHTML = text;
        return this;
    };
    loadingControl.addTo(map);

    // Load data function
    async function loadAirQualityData() {
        try {
            const response = await fetch('api/get_purpleair.php');
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            // Check if data is valid
            if (!data || !Array.isArray(data.data)) {
                throw new Error('Invalid data format from API');
            }

            // Process and add markers
            data.data.forEach(sensor => {
                const [id, pm25, lat, lon, name] = sensor;
                
                // Skip invalid data
                if (!lat || !lon || pm25 === undefined) return;
                
                const aqi = calculateAQI(pm25);
                
                L.circleMarker([lat, lon], {
                    radius: 8,
                    fillColor: getAQIColor(aqi),
                    color: '#000',
                    weight: 1,
                    fillOpacity: 0.8
                }).bindPopup(`
                    <b>${name || 'Unnamed Sensor'}</b><br>
                    PM2.5: ${pm25} µg/m³<br>
                    AQI: ${aqi}
                `).addTo(map);
            });

            // Hide loading overlay
            document.querySelector('.loading-overlay').style.display = 'none';
            loadingControl.update(`Last updated: ${new Date().toLocaleTimeString()}`);

        } catch (error) {
            console.error('Error loading air quality data:', error);
            loadingControl.update('Error loading data');
            
            // Show test marker if API fails
            L.marker([6.9344, 79.8428])
                .bindPopup("<b>Test Marker</b><br>API failed, but Leaflet works!")
                .addTo(map);
            
            document.querySelector('.loading-overlay').style.display = 'none';
        }
    }

    // Helper functions
    function calculateAQI(pm25) {
        if (pm25 <= 12) return Math.round((50/12) * pm25);
        if (pm25 <= 35.4) return Math.round(50 + (50/23.4) * (pm25 - 12));
        if (pm25 <= 55.4) return Math.round(100 + (100/20) * (pm25 - 35.4));
        return Math.round(150 + (100/49.6) * (pm25 - 55.4));
    }

    function getAQIColor(aqi) {
        if (aqi <= 50) return '#4CAF50';
        if (aqi <= 100) return '#FFC107';
        if (aqi <= 150) return '#FF9800';
        return '#F44336';
    }

    // Initial load
    loadAirQualityData();
    
    // Auto-refresh every 5 minutes
    setInterval(loadAirQualityData, 300000);
});