<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? ($clinic->name ?? 'ONEW Clinic') . ' — Rekam Medis & Alur Pelayanan' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Dynamic White-Label Accent Palette -->
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
            filter: brightness(0.92);
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-slate-900 bg-[#f8fafc] flex flex-col min-h-screen selection:bg-slate-200 selection:text-slate-900">
    <!-- Apple-style Translucent Navigation Bar -->
    <header class="sticky top-0 z-40 glass-surface border-b border-slate-200/70 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Brand & Logo -->
                <div class="flex items-center gap-3.5">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group transition-transform active:scale-[0.98]">
                        <img src="{{ asset('logo.svg') }}" alt="{{ $clinic->name ?? 'ONEW Clinic' }}" class="h-11 w-auto object-contain">
                    </a>
                </div>

                <!-- Navigation Pill Segments (Clean 4-Step Architecture) -->
                <nav class="hidden md:flex items-center p-1 rounded-full bg-slate-100/80 border border-slate-200/50 text-xs font-medium">
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-1.5 rounded-full transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white text-slate-950 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        1. Kunjungan Hari Ini
                    </a>

                    @if(auth()->check() && (auth()->user()->isSuperadmin() || auth()->user()->isReceptionist() || auth()->user()->isNurse()))
                        <a href="{{ route('patients.register') }}"
                           class="px-4 py-1.5 rounded-full transition-all duration-200 {{ request()->routeIs('patients.register') ? 'bg-white text-slate-950 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                            2. Registrasi & Consent
                        </a>
                    @endif

                    @if(auth()->check() && (auth()->user()->isSuperadmin() || auth()->user()->isDoctor() || auth()->user()->isNurse()))
                        <a href="{{ route('doctor.treatment') }}"
                           class="px-4 py-1.5 rounded-full transition-all duration-200 {{ request()->routeIs('doctor.treatment') ? 'bg-white text-slate-950 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                            3. Pemeriksaan Medis
                        </a>
                    @endif

                    @if(auth()->check() && auth()->user()->isSuperadmin())
                        <a href="{{ route('clinic.settings') }}"
                           class="px-4 py-1.5 rounded-full transition-all duration-200 {{ request()->routeIs('clinic.settings') ? 'bg-white text-slate-950 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                            Pengaturan
                        </a>
                    @endif
                </nav>

                <!-- Right Profile / Actions -->
                <div class="flex items-center gap-3">
                    @auth
                        <!-- User Role Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" type="button" class="flex items-center gap-2 p-1.5 pr-3 rounded-full border border-slate-200/80 bg-white hover:bg-slate-50 text-xs text-slate-700 font-medium shadow-2xs transition-all active:scale-[0.97]">
                                <span class="w-7 h-7 rounded-full bg-slate-900 text-white flex items-center justify-center font-medium text-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </span>
                                <span class="hidden lg:inline text-xs font-semibold text-slate-800">{{ auth()->user()->name }}</span>
                                <span class="text-[10px] uppercase font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                    {{ auth()->user()->role }}
                                </span>
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-64 rounded-2xl bg-white shadow-apple-elevated border border-slate-200/80 p-2 z-50 text-xs">
                                <div class="px-3.5 py-2.5 border-b border-slate-100">
                                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Pengguna Aktif</span>
                                    <p class="font-semibold text-slate-900 text-sm mt-0.5">{{ auth()->user()->name }}</p>
                                    <p class="text-slate-500 text-[11px]">{{ auth()->user()->email }}</p>
                                    <span class="inline-block mt-1 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-md bg-slate-100 text-slate-700">
                                        Peran: {{ ucfirst(auth()->user()->role) }}
                                    </span>
                                </div>

                                <div class="py-1">
                                    <a href="{{ route('dashboard') }}" class="flex items-center px-3.5 py-2 rounded-xl text-slate-700 hover:bg-slate-100 font-medium">Dashboard Kunjungan</a>
                                    @if(auth()->user()->isSuperadmin() || auth()->user()->isReceptionist() || auth()->user()->isNurse())
                                        <a href="{{ route('patients.register') }}" class="flex items-center px-3.5 py-2 rounded-xl text-slate-700 hover:bg-slate-100 font-medium">Pendaftaran Pasien</a>
                                    @endif
                                    @if(auth()->user()->isSuperadmin() || auth()->user()->isDoctor() || auth()->user()->isNurse())
                                        <a href="{{ route('doctor.treatment') }}" class="flex items-center px-3.5 py-2 rounded-xl text-slate-700 hover:bg-slate-100 font-medium">Ruang Pemeriksaan Medis</a>
                                    @endif
                                    @if(auth()->user()->isSuperadmin())
                                        <a href="{{ route('clinic.settings') }}" class="flex items-center px-3.5 py-2 rounded-xl text-slate-700 hover:bg-slate-100 font-medium">Pengaturan Klinik (Admin)</a>
                                    @endif
                                </div>

                                <div class="border-t border-slate-100 pt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left flex items-center gap-2 px-3.5 py-2 rounded-xl text-rose-600 hover:bg-rose-50 font-medium transition-colors">
                                            <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            <span>Keluar (Logout)</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-900 px-4 py-2 rounded-full border border-slate-200/80 bg-white hover:bg-slate-50 transition-all">
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile / Tablet Portrait Secondary Navigation Bar -->
        <div class="md:hidden border-t border-slate-200/60 bg-white/70 px-4 py-2 flex items-center justify-start overflow-x-auto gap-1 text-xs">
            <a href="{{ route('dashboard') }}" class="px-3.5 py-1.5 rounded-full whitespace-nowrap {{ request()->routeIs('dashboard') ? 'bg-slate-900 text-white font-medium' : 'text-slate-600' }}">1. Kunjungan</a>
            @if(auth()->check() && (auth()->user()->isSuperadmin() || auth()->user()->isReceptionist() || auth()->user()->isNurse()))
                <a href="{{ route('patients.register') }}" class="px-3.5 py-1.5 rounded-full whitespace-nowrap {{ request()->routeIs('patients.register') ? 'bg-slate-900 text-white font-medium' : 'text-slate-600' }}">2. Registrasi & Consent</a>
            @endif
            @if(auth()->check() && (auth()->user()->isSuperadmin() || auth()->user()->isDoctor() || auth()->user()->isNurse()))
                <a href="{{ route('doctor.treatment') }}" class="px-3.5 py-1.5 rounded-full whitespace-nowrap {{ request()->routeIs('doctor.treatment') ? 'bg-slate-900 text-white font-medium' : 'text-slate-600' }}">3. Pemeriksaan</a>
            @endif
            @if(auth()->check() && auth()->user()->isSuperadmin())
                <a href="{{ route('clinic.settings') }}" class="px-3.5 py-1.5 rounded-full whitespace-nowrap {{ request()->routeIs('clinic.settings') ? 'bg-slate-900 text-white font-medium' : 'text-slate-600' }}">Pengaturan</a>
            @endif
        </div>
    </header>

    <!-- Apple-Style Floating Toast Notifications Container -->
    <div x-data="{
            toasts: [],
            add(message, type = 'success') {
                if (!message) return;
                const id = Date.now() + Math.random().toString(36).substring(2, 6);
                this.toasts.push({ id, message, type });
                setTimeout(() => this.remove(id), 4000);
            },
            remove(id) {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }
         }"
         x-init="
            @if(session()->has('success'))
                add(@js(session('success')), 'success');
            @endif
            @if(session()->has('error'))
                add(@js(session('error')), 'error');
            @endif
            @if(session()->has('info'))
                add(@js(session('info')), 'info');
            @endif
         "
         @notify.window="add($event.detail.message, $event.detail.type || 'success')"
         class="fixed top-5 right-5 z-50 flex flex-col gap-2.5 pointer-events-none max-w-sm sm:max-w-md w-full px-4"
         aria-live="polite">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-[-12px] scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-[-8px] scale-95"
                 class="pointer-events-auto flex items-start gap-3 p-4 rounded-2xl bg-white/95 backdrop-blur-xl border border-slate-200/90 shadow-apple-elevated text-xs transition-all">
                
                <!-- Status Icon -->
                <div class="shrink-0 mt-0.5">
                    <template x-if="toast.type === 'success'">
                        <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <div class="w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <div class="w-6 h-6 rounded-full bg-sky-500 text-white flex items-center justify-center shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </template>
                </div>

                <!-- Message Body -->
                <div class="flex-1 min-w-0 pr-1">
                    <p class="font-semibold text-slate-900 text-xs leading-snug tracking-tight" x-text="toast.message"></p>
                    <span class="text-[10px] text-slate-400 mt-0.5 block">Notifikasi Sistem &bull; ONEW Clinic</span>
                </div>

                <!-- Dismiss Button -->
                <button @click="remove(toast.id)" class="shrink-0 text-slate-400 hover:text-slate-700 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </template>
    </div>

    <!-- Main Content Container with Relaxed Apple Breathing Room -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        @if (session()->has('success'))
            <div class="mb-6 rounded-2xl bg-white border border-emerald-200/80 p-4 text-emerald-950 text-xs shadow-apple flex items-center justify-between" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-slate-400 hover:text-slate-700 p-1">&times;</button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 rounded-2xl bg-white border border-rose-200/80 p-4 text-rose-950 text-xs shadow-apple flex items-center justify-between" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-slate-400 hover:text-slate-700 p-1">&times;</button>
            </div>
        @endif

        {{ $slot ?? $content ?? '' }}
    </main>

    <!-- Refined Minimalist Apple Footer -->
    <footer class="border-t border-slate-200/60 bg-white py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-3">
            <div class="flex items-center gap-2">
                <span class="font-semibold text-slate-800">{{ $clinic->name ?? 'ONEW Clinic' }}</span>
                <span>&bull;</span>
                <span>{{ $clinic->address ?? 'Klinik Rekam Medis Estetika' }}</span>
                @if(!empty($clinic->phone))
                    <span>&bull;</span>
                    <span>{{ $clinic->phone }}</span>
                @endif
            </div>
            <div class="text-slate-400">
                Sistem Rekam Medis Terintegrasi
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
