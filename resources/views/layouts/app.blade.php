<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? ($clinic->name ?? 'ONEW Clinic') . ' - Sistem Rekam Medis & Kunjungan Pasien' }}</title>

    <!-- Dynamic White-Label CSS Custom Properties -->
    <style>
        :root {
            --color-primary: {{ $clinic->primary_color ?? '#0284C7' }};
            --color-secondary: {{ $clinic->secondary_color ?? '#0EA5E9' }};
        }

        .bg-clinic-primary {
            background-color: var(--color-primary) !important;
        }
        .text-clinic-primary {
            color: var(--color-primary) !important;
        }
        .border-clinic-primary {
            border-color: var(--color-primary) !important;
        }
        .ring-clinic-primary {
            --tw-ring-color: var(--color-primary) !important;
        }
        .hover\:bg-clinic-primary-dark:hover {
            filter: brightness(0.9);
        }
    </style>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50 flex flex-col min-h-screen">
    <!-- White-Label Navigation Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Brand & Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        @if(!empty($clinic->logo_path))
                            <img src="{{ Storage::url($clinic->logo_path) }}" alt="{{ $clinic->name }}" class="h-10 w-auto rounded-lg object-contain">
                        @else
                            <div class="w-10 h-10 rounded-xl bg-clinic-primary text-white flex items-center justify-center font-bold text-lg shadow-sm group-hover:scale-105 transition-transform">
                                {{ substr($clinic->clinic_code ?? 'ON', 0, 2) }}
                            </div>
                        @endif
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-lg text-slate-900 tracking-tight">{{ $clinic->name ?? 'ONEW Clinic' }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full font-mono font-semibold bg-sky-50 text-sky-700 border border-sky-200">
                                    {{ $clinic->clinic_code ?? 'ONEW' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 hidden sm:block">Aesthetic Medical Record & Flow System</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('patients.register') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('patients.register') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        1. Registrasi Pasien
                    </a>
                    <a href="{{ route('nurse.examination') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('nurse.examination') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        2. Pemeriksaan Perawat
                    </a>
                    <a href="{{ route('doctor.treatment') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('doctor.treatment') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        3. Tindakan Dokter
                    </a>
                    <a href="{{ route('clinic.settings') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('clinic.settings') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Pengaturan White-Label
                    </a>
                </nav>

                <!-- Right Gateway Status Indicator & Help -->
                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex items-center gap-2 px-2.5 py-1 rounded-full text-xs bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="font-medium">Trustmedis Mock: Online</span>
                    </div>

                    <!-- Role Badge / Simulator dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="flex items-center gap-2 p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-xs text-slate-600 font-medium">
                            <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs">
                                DR
                            </span>
                            <span class="hidden md:inline">dr. Alana Wijaya</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-56 rounded-xl bg-white shadow-lg border border-slate-100 py-2 z-50 text-sm">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-400">Login Aktif (Simulasi)</p>
                                <p class="font-medium text-slate-800">dr. Alana Wijaya, Sp.D.V.E</p>
                                <p class="text-xs text-slate-500 capitalize">Role: Doctor</p>
                            </div>
                            <a href="{{ route('patients.register') }}" class="block px-4 py-2 hover:bg-slate-50 text-slate-700">Mode Resepsionis</a>
                            <a href="{{ route('nurse.examination') }}" class="block px-4 py-2 hover:bg-slate-50 text-slate-700">Mode Perawat</a>
                            <a href="{{ route('doctor.treatment') }}" class="block px-4 py-2 hover:bg-slate-50 text-slate-700">Mode Dokter</a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <a href="{{ route('clinic.settings') }}" class="block px-4 py-2 hover:bg-slate-50 text-slate-700">Kelola White-Label Klinik</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Nav Menu Bar -->
        <div class="md:hidden border-t border-slate-200 bg-slate-50 px-4 py-2 flex items-center justify-between overflow-x-auto text-xs gap-2">
            <a href="{{ route('dashboard') }}" class="whitespace-nowrap px-2 py-1 rounded {{ request()->routeIs('dashboard') ? 'font-bold text-sky-700' : 'text-slate-600' }}">Dashboard</a>
            <a href="{{ route('patients.register') }}" class="whitespace-nowrap px-2 py-1 rounded {{ request()->routeIs('patients.register') ? 'font-bold text-sky-700' : 'text-slate-600' }}">1. Registrasi</a>
            <a href="{{ route('nurse.examination') }}" class="whitespace-nowrap px-2 py-1 rounded {{ request()->routeIs('nurse.examination') ? 'font-bold text-sky-700' : 'text-slate-600' }}">2. Perawat</a>
            <a href="{{ route('doctor.treatment') }}" class="whitespace-nowrap px-2 py-1 rounded {{ request()->routeIs('doctor.treatment') ? 'font-bold text-sky-700' : 'text-slate-600' }}">3. Dokter</a>
            <a href="{{ route('clinic.settings') }}" class="whitespace-nowrap px-2 py-1 rounded {{ request()->routeIs('clinic.settings') ? 'font-bold text-sky-700' : 'text-slate-600' }}">White-Label</a>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session()->has('success'))
            <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-sm flex items-center justify-between" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">&times;</button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 p-4 text-rose-800 text-sm flex items-center justify-between" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-500 hover:text-rose-700">&times;</button>
            </div>
        @endif

        {{ $slot ?? $content ?? '' }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
            <div>
                <span class="font-semibold text-slate-700">{{ $clinic->name ?? 'ONEW Clinic' }}</span> &bull; {{ $clinic->address ?? 'Klinik Rekam Medis Estetika' }}
                @if(!empty($clinic->phone))
                    &bull; Telp: {{ $clinic->phone }}
                @endif
            </div>
            <div class="flex items-center gap-3">
                <span class="text-slate-400">Laravel 12 &bull; Livewire 3 &bull; Alpine.js &bull; Tailwind CSS</span>
                <span class="px-2 py-0.5 rounded bg-slate-100 font-mono text-[10px] text-slate-600">MVP v1.0</span>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
