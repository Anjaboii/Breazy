import './globals.css'
import type { Metadata } from 'next'
import 'leaflet/dist/leaflet.css'


export const metadata: Metadata = {
  title: 'AQI Dashboard',
  description: 'Air Quality Index Monitoring Dashboard',
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  )
}