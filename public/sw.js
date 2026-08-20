const CACHE_NAME = 'flavory-pos-v10';
const URLS_TO_CACHE = [
    '/kasir',
    '/kasir/order/create',
    '/manifest.json',
    '/pwa-auth.js',
    '/pwa-order.js',
    '/pwa-kasir.js',
    '/logo-192.png',
    '/logo-512.png'
];

// Halaman yang TIDAK BOLEH di-cache (harus selalu fresh dari server)
const NEVER_CACHE = ['/login'];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(URLS_TO_CACHE);
        })
    );
    // Aktifkan SW baru segera tanpa tunggu tab lama ditutup
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    // Hapus cache lama saat SW baru aktif
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k)))
        ).then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', event => {
    // Hanya handle request GET
    if (event.request.method !== 'GET') return;

    const url = new URL(event.request.url);

    // Halaman auth tidak boleh di-cache sama sekali — selalu ke network
    if (NEVER_CACHE.some(path => url.pathname === path || url.pathname.startsWith(path))) {
        event.respondWith(fetch(event.request));
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then(response => {
                // HANYA simpan ke cache jika respons valid dan sukses (status 200)
                if (response && response.status === 200 && response.type === 'basic') {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                // Jika jaringan benar-benar offline/gagal, ambil dari cache
                return caches.match(event.request).then(response => {
                    return response || caches.match('/kasir');
                });
            })
    );
});