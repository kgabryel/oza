const cacheName = 'cache';
self.addEventListener('install', () => {
    caches.open(cacheName).then(cache => {
        cache.addAll(['/offline-page.html']);
    });
})
self.addEventListener('fetch', evt => {
    evt.respondWith(
        caches.match(evt.request).then(cacheRes => {
            return cacheRes || fetch(evt.request).then(fetchRes => {
                return fetchRes;
            });
        }).catch(() => caches.match('/offline-page.html'))
    );
});