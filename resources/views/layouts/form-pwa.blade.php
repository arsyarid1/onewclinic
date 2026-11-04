<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'ONEW Clinic — Alur Pendaftaran & Rekam Medis' }}</title>

    <!-- Favicon & PWA Icons -->
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="manifest" href="/manifest.json">

    <!-- PWA / Web App Capabilities - Standalone without address bar -->
    <meta name="theme-color" content="#0f172a">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Form Flow">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Form Flow">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="192x192" href="/icons/icon-192.png">
    <link rel="apple-touch-icon" sizes="512x512" href="/icons/icon-512.png">

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
            }
            .paper-form {
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-slate-900 bg-slate-100 flex flex-col min-h-screen selection:bg-slate-200 selection:text-slate-900">
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
                </div>

                <div class="flex-1 min-w-0 pr-1">
                    <p class="font-semibold text-slate-900 text-xs leading-snug tracking-tight" x-text="toast.message"></p>
                    <span class="text-[10px] text-slate-400 mt-0.5 block">Notifikasi Sistem &bull; ONEW Clinic</span>
                </div>

                <button @click="remove(toast.id)" class="shrink-0 text-slate-400 hover:text-slate-700 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </template>
    </div>

    <!-- Desktop / Tablet PWA Install Floating Banner -->
    <div x-data="{
            deferredPrompt: null,
            canInstall: false,
            isStandalone: window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true,
            init() {
                window.addEventListener('beforeinstallprompt', (e) => {
                    e.preventDefault();
                    this.deferredPrompt = e;
                    this.canInstall = true;
                });
                window.addEventListener('appinstalled', () => {
                    this.canInstall = false;
                    this.deferredPrompt = null;
                });
            },
            installApp() {
                if (this.deferredPrompt) {
                    this.deferredPrompt.prompt();
                    this.deferredPrompt.userChoice.then((choice) => {
                        if (choice.outcome === 'accepted') {
                            this.canInstall = false;
                        }
                        this.deferredPrompt = null;
                    });
                }
            }
         }"
         x-show="canInstall && !isStandalone"
         x-cloak
         class="fixed top-3 left-1/2 -translate-x-1/2 z-50 no-print">
        <button type="button"
                @click="installApp()"
                class="px-4 py-2 rounded-full bg-slate-900 text-white text-xs font-semibold shadow-apple-elevated flex items-center gap-2 hover:bg-black transition-all active:scale-95 border border-slate-700">
            <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            <span>Pasang Aplikasi ke Desktop (PWA Tanpa Address Bar)</span>
        </button>
    </div>

    <!-- Main Content Container -->
    <main class="flex-1 flex flex-col w-full pb-28">
        {{ $slot }}
    </main>

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => {
                    console.error('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>

    @include('partials.photo-uploader-script')
    @livewireScripts
</body>
</html>
