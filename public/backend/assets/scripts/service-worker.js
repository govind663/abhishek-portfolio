// ========================================
// 🚀 Abhishek Admin Service Worker FINAL
// ========================================

const CACHE_NAME = 'abhishek-admin-v4';
const STATIC_CACHE = 'abhishek-admin-static-v4';

// ✅ Safe cache files
const urlsToCache = [
  '/backend/dashboard',
  '/backend/assets/vendors/styles/core.css',
  '/backend/assets/vendors/styles/icon-font.min.css',
  '/backend/assets/vendors/styles/style.css',
  '/backend/assets/favicon.png'
];

// ================= INSTALL =================
self.addEventListener('install', event => {
  console.log('🚀 SW Installing...');

  event.waitUntil(
    caches.open(STATIC_CACHE)
      .then(cache => {
        return Promise.allSettled(
          urlsToCache.map(url => cache.add(url))
        );
      })
      .then(() => self.skipWaiting())
  );
});

// ================= ACTIVATE =================
self.addEventListener('activate', event => {
  console.log('✅ SW Activated');

  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== STATIC_CACHE && cache !== CACHE_NAME) {
            console.log('🧹 Removing old cache:', cache);
            return caches.delete(cache);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// ================= FETCH =================
self.addEventListener('fetch', event => {

  if (event.request.method !== 'GET') return;

  if (event.request.url.includes('/backend/')) {

    event.respondWith(
      caches.match(event.request)
        .then(response => {

          // ✅ Cache Hit
          if (response) return response;

          // 🌐 Network Request
          return fetch(event.request)
            .then(networkResponse => {

              if (!networkResponse || networkResponse.status !== 200) {
                return networkResponse;
              }

              const clone = networkResponse.clone();

              caches.open(CACHE_NAME)
                .then(cache => cache.put(event.request, clone));

              return networkResponse;
            })
            .catch(() => {
              console.log('⚠️ Offline fallback');
              return caches.match('/backend/dashboard');
            });
        })
    );
  }
});

// ================= UPDATE CONTROL =================

// 🔥 Receive message from frontend
self.addEventListener('message', event => {
  if (event.data === 'SKIP_WAITING') {
    console.log('⚡ Skipping waiting...');
    self.skipWaiting();
  }
});

// ================= PUSH NOTIFICATION =================
self.addEventListener('push', event => {

  const data = event.data ? event.data.text() : 'New notification received';

  const options = {
    body: data,
    icon: '/backend/assets/favicon.png',
    badge: '/backend/assets/favicon.png',
    vibrate: [100, 50, 100],
    data: {
      date: new Date().toISOString()
    },
    actions: [
      { action: 'open', title: 'Open' },
      { action: 'close', title: 'Close' }
    ]
  };

  event.waitUntil(
    self.registration.showNotification('🚀 Admin Notification', options)
  );
});

// ================= NOTIFICATION CLICK =================
self.addEventListener('notificationclick', event => {
  event.notification.close();

  if (event.action === 'open') {
    event.waitUntil(
      clients.openWindow('/backend/dashboard')
    );
  }
});

// ================= BACKGROUND SYNC =================
self.addEventListener('sync', event => {
  if (event.tag === 'sync-forms') {
    event.waitUntil(syncPendingForms());
  }
});

function syncPendingForms() {
  console.log('🔄 Background sync running...');
}