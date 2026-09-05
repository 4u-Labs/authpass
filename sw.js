const CACHE_NAME = 'authpass-v1.0.0';
const ASSETS = [
  './',
  './index.php',
  './manifest.json',
  './favicon-32x32.png',
  './icon-192.png',
  './icon-512.png',
  './privacidade.php',
  './termos.php',
  './suporte.php'
];

self.addEventListener('install', (e) => {
  e.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(ASSETS);
    }).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (e) => {
  e.waitUntil(
    caches.keys().then((keys) => {
      return Promise.all(
        keys.map((key) => {
          if (key !== CACHE_NAME) {
            return caches.delete(key);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (e) => {
  // Ignora chamadas a APIs externas (ex: Google OAuth, CDNs de fontes, etc)
  if (!e.request.url.startsWith(self.location.origin)) {
    return;
  }

  e.respondWith(
    caches.match(e.request).then((cached) => {
      if (cached) return cached;
      return fetch(e.request).then((response) => {
        if (!response || response.status !== 200 || response.type !== 'basic') {
          return response;
        }
        const toCache = response.clone();
        caches.open(CACHE_NAME).then((cache) => cache.put(e.request, toCache));
        return response;
      }).catch(() => caches.match('./index.php'));
    })
  );
});
