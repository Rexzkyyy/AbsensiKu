const CACHE_NAME = 'absensiku-cache-v3';

const urlsToCache = [
  '/offline.html',
  '/manifest.json',
  '/assets/img/logo_login.png',
  '/assets/img/logo_login.webp',
  '/assets/icons/icon-192.png',
  '/assets/icons/icon-512.png'
];

self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', event => {
  if (event.request.method !== 'GET' || !event.request.url.startsWith(self.location.origin)) {
    return;
  }

  // Untuk navigasi halaman (HTML), gunakan Network First, fallback ke offline.html
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request).catch(() => caches.match('/offline.html'))
    );
    return;
  }

  // Tentukan apakah request ini adalah aset statis (gambar, css, js, font)
  const isStaticAsset = event.request.url.match(/\.(png|jpg|jpeg|gif|svg|webp|css|js|woff2?|ttf|eot)$/i) || 
                        event.request.url.includes('/assets/');

  if (isStaticAsset) {
    // Untuk aset statis, gunakan Cache First, fallback ke Network lalu cache hasilnya
    event.respondWith(
      caches.match(event.request).then(cachedResponse => {
        if (cachedResponse) {
          return cachedResponse;
        }

        return fetch(event.request).then(response => {
          if (!response || response.status !== 200 || response.type !== 'basic') {
            return response;
          }
          const responseToCache = response.clone();
          caches.open(CACHE_NAME).then(cache => cache.put(event.request, responseToCache));
          return response;
        }).catch(() => {
          // Abaikan error jaringan untuk aset statis agar tidak memblokir render
        });
      })
    );
  } else {
    // Untuk request GET lainnya (seperti AJAX, Fetch HTML, API), gunakan Network Only
    // Ini mencegah caching CSRF token yang sudah kedaluwarsa (419 Page Expired)
    event.respondWith(fetch(event.request));
  }
});
