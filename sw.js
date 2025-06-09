const CACHE_NAME = 'agc-pwa-static-v6';

const STATIC_ASSETS = [
  'offline.html',
  'assets/images/web-app-manifest-192x192.png',
  'assets/images/web-app-manifest-512x512.png',
  'assets/images/notification-icon.png',
  'assets/images/badge-icon.png',
  'assets/images/apple-touch-icon.png',
  'manifest.json'
];

// Installation
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(STATIC_ASSETS))
  );
  self.skipWaiting();
});

// Activation : suppression des anciens caches s’il y en a
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys.filter(key => key !== CACHE_NAME)
            .map(key => caches.delete(key))
      )
    )
  );
  self.clients.claim();
});


// Fetch : uniquement servir offline.html si l’utilisateur est hors ligne
self.addEventListener('fetch', event => {
  // Si c’est une requête de navigation (HTML)
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request).catch(() => caches.match('./offline.html'))
    );
  }

  // Sinon (images ou autres), on essaie juste de servir depuis le cache (icônes par exemple)
  else if (STATIC_ASSETS.includes(new URL(event.request.url).pathname)) {
    event.respondWith(
      caches.match(event.request)
    );
  }

  // Sinon (CSS, JS, etc.), laisse le navigateur gérer (aucun cache)
});

// Push notification
self.addEventListener('push', event => {
  const data = event.data.json();

  const options = {
    body: data.body,
    icon: 'assets/images/notification-icon.png',
    badge: 'assets/images/badge-icon.png',
    data: {
      url: data.url,
      id: data.id,
      type: data.type
    }
  };

  event.waitUntil(
    self.registration.showNotification(data.title, options)
  );
});

// Click sur notification
self.addEventListener('notificationclick', function(event) {
  const notifData = event.notification.data;
  event.notification.close();

  // Marquer comme lu via une requête POST (is_read = 1)
  fetch('mark_notification_read.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `type=${notifData.type}&id=${notifData.id}`
  });

  // Ouvrir l'URL liée
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then(clientList => {
      for (const client of clientList) {
        if (client.url === notifData.url && 'focus' in client) {
          return client.focus();
        }
      }
      if (clients.openWindow) {
        return clients.openWindow(notifData.url);
      }
    })
  );
});
