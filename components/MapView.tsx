import React, { useEffect, useState } from 'react';
import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import MarkerClusterGroup from 'react-leaflet-cluster';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

delete (L.Icon.Default.prototype as any)._getIconUrl;
L.Icon.Default.mergeOptions({
  iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.3/dist/images/marker-icon-2x.png',
  iconUrl: 'https://unpkg.com/leaflet@1.9.3/dist/images/marker-icon.png',
  shadowUrl: 'https://unpkg.com/leaflet@1.9.3/dist/images/marker-shadow.png',
});

const getColorByAQI = (aqi: number) => {
  if (aqi <= 50) return 'green';
  if (aqi <= 100) return 'yellow';
  if (aqi <= 150) return 'orange';
  if (aqi <= 200) return 'red';
  if (aqi <= 300) return 'purple';
  return 'maroon';
};

const CustomMarker = ({ lat, lng, aqi }: { lat: number; lng: number; aqi: number }) => {
  const markerHtmlStyles = `
    background-color: ${getColorByAQI(aqi)};
    width: 2rem;
    height: 2rem;
    display: block;
    left: -1rem;
    top: -1rem;
    position: relative;
    border-radius: 3rem 3rem 0;
    transform: rotate(45deg);
    border: 1px solid #FFFFFF;
  `;

  const icon = L.divIcon({
    className: "custom-icon",
    iconAnchor: [0, 0],
    popupAnchor: [0, -10],
    html: `<span style="${markerHtmlStyles}" />`,
  });

  return (
    <Marker position={[lat, lng]} icon={icon}>
      <Popup>AQI: {aqi}</Popup>
    </Marker>
  );
};

const MapView = () => {
  const [aqiData, setAqiData] = useState<
    { id: string; lat: number; lng: number; value: number }[]
  >([]);

  useEffect(() => {
    const fetchAQI = async () => {
      try {
        const res = await fetch(
          'https://api.openaq.org/v2/latest?coordinates=6.9271,79.8612&radius=10000'
        );
        const json = await res.json();

        const parsed = json.results
          .filter((station: any) => station.coordinates)
          .map((station: any) => ({
            id: station.location,
            lat: station.coordinates.latitude,
            lng: station.coordinates.longitude,
            value: station.measurements[0]?.value || 0,
          }));

        setAqiData(parsed);
      } catch (err) {
        console.error('Failed to load AQI data', err);
      }
    };

    fetchAQI();
  }, []);

  return (
    <div style={{ height: '100vh', width: '100%' }}>
      <MapContainer
        center={[6.9271, 79.8612]}
        zoom={12}
        scrollWheelZoom={true}
        style={{ height: '100%', width: '100%' }}
      >
        <TileLayer
          attribution='&copy; OpenStreetMap'
          url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
        />
        <MarkerClusterGroup>
          {aqiData.map((sensor) => (
            <CustomMarker
              key={sensor.id}
              lat={sensor.lat}
              lng={sensor.lng}
              aqi={sensor.value}
            />
          ))}
        </MarkerClusterGroup>
      </MapContainer>
    </div>
  );
};

export default MapView;
