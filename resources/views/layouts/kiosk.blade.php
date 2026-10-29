<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#f1f5f9] select-none">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ ($clinic->name ?? 'ONEW Clinic') }} — Persetujuan Pasien</title>

    <style>
        :root {
            --color-primary: {{ $clinic->primary_color ?? '#0284C7' }};
            --color-secondary: {{ $clinic->secondary_color ?? '#0EA5E9' }};
        }
        .bg-clinic-primary { background-color: var(--color-primary) !important; }
        .text-clinic-primary { color: var(--color-primary) !important; }
        .border-clinic-primary { border-color: var(--color-primary) !important; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-slate-900 bg-[#f1f5f9] flex flex-col justify-between overflow-x-hidden">
    <!-- Apple-style Pure Header for Patient Tablet -->
    <header class="glass-surface border-b border-slate-200/80 py-4 px-6 sm:px-10 sticky top-0 z-30">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <img src="{{ asset('onew_navbar_logo.webp') }}" alt="{{ $clinic->name ?? 'ONEW Clinic' }}" class="h-10 w-auto object-contain">
                <div>
                    <h1 class="text-lg font-bold text-slate-900 tracking-tight leading-none">{{ $clinic->name ?? 'ONEW Clinic' }}</h1>
                    <p class="text-[11px] text-slate-500 font-medium mt-1">Persetujuan Pasien (General Consent)</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200/80">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Mode Tablet Pasien
                </span>
            </div>
        </div>
    </header>

    <!-- Main Tablet Kiosk Workspace -->
    <main class="flex-1 py-8 px-4 sm:px-8 flex items-center justify-center">
        <div class="max-w-3xl w-full">
            {{ $slot ?? $content ?? '' }}
        </div>
    </main>

    <!-- Discreet Footer -->
    <footer class="py-4 px-6 text-center text-xs text-slate-400 font-normal">
        {{ $clinic->name ?? 'ONEW Clinic' }} &bull; Rekam Medis Elektronik Terstandarisasi
    </footer>

    @livewireScripts
</body>
</html>
