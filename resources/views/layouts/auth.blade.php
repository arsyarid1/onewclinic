<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Masuk — ONEW Clinic Sistem Rekam Medis</title>

    <!-- Favicon & PWA Icons -->
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- PWA / Web App Capabilities -->
    <meta name="theme-color" content="#0284C7">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="ONEW Clinic">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="ONEW Clinic">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icon-192.png') }}">
    <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('icon-512.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-slate-900 bg-[#f8fafc] flex flex-col justify-center items-center p-4 sm:p-6 selection:bg-slate-200 selection:text-slate-900">
    {{ $slot }}

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((reg) => {
                        console.log('ONEW Clinic PWA ServiceWorker active:', reg.scope);
                    })
                    .catch((err) => {
                        console.warn('PWA ServiceWorker registration notice:', err);
                    });
            });
        }
    </script>

    @livewireScripts
</body>
</html>
