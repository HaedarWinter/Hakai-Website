const CACHE_NAME = 'hakai-cache-v2';
const urlsToCache = [
    '/',
    '/css/custom-sidebar.css',
    '/css/dark-mode.css',
    '/css/enhancements.css',
    '/sbadmin2/css/sb-admin-2.min.css',
    '/sbadmin2/vendor/fontawesome-free/css/all.min.css',
    '/sbadmin2/vendor/jquery/jquery.min.js',
    '/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js',
    '/sbadmin2/js/sb-admin-2.min.js'
];

// Install event
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
            .catch(err => console.log('Cache install failed:', err))
    );
});

// Fetch event
// Fetch event - Network First for HTML, Cache First for assets
self.addEventListener('fetch', event => {
    // Only handle http/https requests
    if (!event.request.url.startsWith('http')) {
        return;
    }

    // Ignore POST requests (always go to network)
    if (event.request.method !== 'GET') {
        return;
    }

    const isHTML = event.request.headers.get('accept').includes('text/html');

    if (isHTML) {
        // Network First for HTML
        event.respondWith(
            fetch(event.request)
                .then(response => {
                    // Cache the fresh copy
                    const responseToCache = response.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, responseToCache);
                    });
                    return response;
                })
                .catch(() => {
                    // If network fails, try cache
                    return caches.match(event.request);
                })
        );
    } else {
        // Cache First for Assets (CSS, JS, Images, etc.)
        event.respondWith(
            caches.match(event.request)
                .then(response => {
                    if (response) {
                        return response;
                    }
                    return fetch(event.request).then(response => {
                        // Cache the new asset
                        if (!response || response.status !== 200 || response.type !== 'basic') {
                            return response;
                        }
                        const responseToCache = response.clone();
                        caches.open(CACHE_NAME).then(cache => {
                            cache.put(event.request, responseToCache);
                        });
                        return response;
                    });
                })
        );
    }
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
