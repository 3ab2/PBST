const CACHE_NAME = 'pbst-static-v1';
const CORE_ASSETS = [
  '/pbst_app/',
  '/pbst_app/index.php',
  '/pbst_app/offline.html',
  '/pbst_app/manifest.webmanifest',
  '/pbst_app/assets/css/app.css',
  '/pbst_app/assets/js/app.js',
  '/pbst_app/assets/icons/icon-192.png',
  '/pbst_app/assets/icons/icon-512.png'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(CORE_ASSETS))
  );
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k)))
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', event => {
  if (event.request.mode === 'navigate' || (event.request.method === 'GET' && event.request.headers.get('accept')?.includes('text/html'))) {
    event.respondWith(
      fetch(event.request).then(resp => {
        const copy = resp.clone();
        caches.open(CACHE_NAME).then(cache => cache.put(event.request, copy));
        return resp;
      }).catch(() => caches.match('/pbst_app/offline.html'))
    );
    return;
  }

  event.respondWith(
    caches.match(event.request).then(cached => cached || fetch(event.request).catch(()=>caches.match('/pbst_app/offline.html')))
  );
});
