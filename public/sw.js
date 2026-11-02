const CACHE_NAME = 'onew-clinic-v2';
const PRECACHE_ASSETS = [
    '/offline.html',
    '/manifest.json',
    '/favicon.ico',
    '/icon.png',
    '/icon-192.png',
    '/icon-512.png',
    '/apple-touch-icon.png',
    '/logo.svg'
];

// Install: Cache essential shell & icons
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_ASSETS);
        }).then(() => self.skipWaiting())
    );
});

// Activate: Clean up old caches & take control immediately
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        return caches.delete(cache);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch handler
self.addEventListener('fetch', (event) => {
    const request = event.request;

    // Never intercept non-GET requests (e.g. POST for login, form submissions, logout)
    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    // Bypass Livewire internal endpoints, API sync, and storage uploads (photos, signatures)
    if (
        url.pathname.startsWith('/livewire/') ||
        url.pathname.startsWith('/api/') ||
        url.pathname.startsWith('/storage/')
    ) {
        return;
    }

    // 1. Navigation requests (HTML pages): Network-only with offline fallback (no caching dynamic pages)
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request).catch(() => {
                return caches.match('/offline.html');
            })
        );
        return;
    }

    // 2. Static Assets (Icons, Fonts, Build files): Stale-While-Revalidate
    if (
        url.pathname.startsWith('/build/') ||
        url.pathname.endsWith('.woff2') ||
        url.pathname.endsWith('.woff') ||
        url.pathname.endsWith('.css') ||
        url.pathname.endsWith('.js')
    ) {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                const fetchPromise = fetch(request).then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200) {
                        const responseClone = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(request, responseClone));
                    }
                    return networkResponse;
                }).catch(() => null);

                return cachedResponse || fetchPromise;
            })
        );
        return;
    }

    // Default: Network fetch
    event.respondWith(
        fetch(request).catch(() => caches.match(request))
    );
});
