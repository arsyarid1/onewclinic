<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100 select-none">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ ($clinic->name ?? 'ONEW Clinic') }} - Tablet Kiosk Pasien</title>

    <style>
        :root {
            --color-primary: {{ $clinic->primary_color ?? '#0284C7' }};
            --color-secondary: {{ $clinic->secondary_color ?? '#0EA5E9' }};
        }
        .bg-clinic-primary { background-color: var(--color-primary) !important; }
        .text-clinic-primary { color: var(--color-primary) !important; }
        .border-clinic-primary { border-color: var(--color-primary) !important; }
    </style>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-100 flex flex-col justify-between overflow-x-hidden">
    <!-- Patient Kiosk Brand Bar -->
    <header class="bg-white border-b border-slate-200 py-4 px-6 shadow-xs">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if(!empty($clinic->logo_path))
                    <img src="{{ Storage::url($clinic->logo_path) }}" alt="{{ $clinic->name }}" class="h-10 w-auto rounded-lg object-contain">
                @else
                    <div class="w-10 h-10 rounded-xl bg-clinic-primary text-white flex items-center justify-center font-bold text-lg shadow-sm">
                        {{ substr($clinic->clinic_code ?? 'ON', 0, 2) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">{{ $clinic->name ?? 'ONEW Clinic' }}</h1>
                    <p class="text-xs text-slate-500">Persetujuan Pasien (General Consent Tablet Mode)</p>
                </div>
            </div>

            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span>
                    Mode Kiosk Pasien
                </span>
            </div>
        </div>
    </header>

    <!-- Main Tablet Kiosk Content -->
    <main class="flex-1 py-6 px-4 sm:px-6 flex items-center justify-center">
        <div class="max-w-4xl w-full">
            {{ $slot ?? $content ?? '' }}
        </div>
    </main>

    <!-- Kiosk Subtle Footer -->
    <footer class="py-3 px-6 text-center text-xs text-slate-400">
        {{ $clinic->name ?? 'ONEW Clinic' }} &bull; Layanan Rekam Medis Elektronik Terstandarisasi &bull; {{ date('Y') }}
    </footer>

    @livewireScripts
</body>
</html>
