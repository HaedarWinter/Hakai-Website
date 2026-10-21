const CACHE_NAME = 'hakai-cache-v3';
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
    self.skipWaiting();
});

// Fetch event
// Network First for HTML. Stale-while-revalidate for assets so a CSS change
// shows up on the next load instead of waiting for a manual version bump.
self.addEventListener('fetch', event => {
    if (!event.request.url.startsWith('http')) {
        return;
    }

    // Ignore POST requests (always go to network)
    if (event.request.method !== 'GET') {
        return;
    }

    const accept = event.request.headers.get('accept') || '';
    const isHTML = accept.includes('text/html');

    if (isHTML) {
        event.respondWith(
            fetch(event.request)
                .then(response => {
                    const responseToCache = response.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, responseToCache);
                    });
                    return response;
                })
                .catch(() => caches.match(event.request))
        );
        return;
    }

    event.respondWith(
        caches.match(event.request).then(cached => {
            const network = fetch(event.request)
                .then(response => {
                    if (response && response.status === 200 && response.type === 'basic') {
                        const responseToCache = response.clone();
                        caches.open(CACHE_NAME).then(cache => {
                            cache.put(event.request, responseToCache);
                        });
                    }
                    return response;
                })
                .catch(() => cached);

            return cached || network;
        })
    );
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
        }).then(() => self.clients.claim())
    );
});
