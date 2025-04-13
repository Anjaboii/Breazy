'use client';

import { useEffect, useState } from 'react';
import { Flame, LayoutGrid, Map, Bell, LineChart, Settings, LogOut, Plus, Minus } from 'lucide-react';
import { cn } from '@/lib/utils';
import dynamic from 'next/dynamic';


const MapView = dynamic(() => import('@/components/MapView'), { ssr: false });

export default function AqiDashboard() {
  const [activePage, setActivePage] = useState('map');

  const menuItems = [
    { id: 'map', icon: Map, label: 'AQI Map' },
    { id: 'dashboard', icon: LayoutGrid, label: 'Dashboard' },
    { id: 'alerts', icon: Bell, label: 'Alerts' },
    { id: 'history', icon: LineChart, label: 'AQI History' },
    { id: 'settings', icon: Settings, label: 'Sensor Settings' },
  ];

  return (
    <div className="flex h-screen flex-col bg-[#121212] text-white">
      {/* Header */}
      <header className="border-b border-gray-800 px-6 py-4 h-15">
        <h5 className="text-xl font-medium">Air Quality Index Monitoring Simulation</h5>
        <div className="absolute right-4 top-3 flex items-center gap-200">
          <span className="text-gray-400">Tue, Apr 01</span>
          <div className="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
            <span>A</span>
          </div>
        </div>
      </header>

      <div className="flex flex-1 overflow-hidden">
        {/* Sidebar */}
        <aside className="border border-gray-800 px-4 py-4 h-10">
          <div className="flex items-center gap-0">
            <img src="Logo.png" alt="Breazy Logo" className="h-6 w-6" />
            <span className="font-medium">Breazy</span>
          </div>

          <div className="border-b border-gray-800 p-4">
            <div className="flex items-center gap-3">
              <div className="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                <span>A</span>
              </div>
              <div>
                <div className="font-medium">Admin</div>
                <div className="text-sm text-gray-400">Administrator</div>
              </div>
            </div>
          </div>

          {/* Navigation */}
          <nav className="py-2">
            {menuItems.map((item) => (
              <button
                key={item.id}
                className={cn(
                  'flex w-full items-center gap-3 px-4 py-3 text-left hover:bg-gray-800',
                  activePage === item.id && 'border-l-2 border-blue-500 bg-gray-800'
                )}
                onClick={() => setActivePage(item.id)}
              >
                <item.icon className={cn('h-8 w-8', item.color || 'text-gray-400')} />
                <span className={cn(activePage === item.id ? 'text-white' : 'text-gray-400')}>{item.label}</span>
              </button>
            ))}
          </nav>

          {/* Logout */}
          <div className="absolute bottom-0 w-60 border-t border-gray-800">
            <button className="flex w-full items-center gap-3 px-4 py-3 text-left text-gray-400 hover:bg-gray-800">
              <LogOut className="h-5 w-5" />
              <span>Logout</span>
            </button>
          </div>
        </aside>

        {/* Main Content */}
        <main className="relative flex-1">
          <div className="h-full w-full bg-[#1a1a1a]">
            <div className="absolute right-4 top-4 z-10 flex flex-col">
              <button className="rounded-t-md bg-[#1a1a1a] p-2 hover:bg-gray-800">
                <Plus className="h-5 w-5" />
              </button>
              <button className="rounded-b-md bg-[#1a1a1a] p-2 hover:bg-gray-800">
                <Minus className="h-5 w-5" />
              </button>
            </div>

            {/* 👇 Load actual map here instead of placeholder */}
            <div className="h-full w-full overflow-hidden">
              {activePage === 'map' && <MapView />}
            </div>
          </div>
        </main>
      </div>
    </div>
  );
}
