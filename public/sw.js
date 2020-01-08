// The activate handler takes care of cleaning up old caches.
var cacheVersion = 1;
var currentCache = {
  offline: 'Yo!kart-offline' + cacheVersion
};
const offlineUrl = '/offline.html';
const cacheName = currentCache.offline;

self.addEventListener('install', function(e) {
  e.waitUntil(
    // Get all the cache keys (cacheName)
  caches.keys().then(function(cacheName) {
    return Promise.all(cacheName.map(function(thisCacheName) {
      // If a cached item is saved under a previous cacheName
      if (thisCacheName !== cacheName) {
        // Delete that cached file
        //console.log('[ServiceWorker] Removing Cached Files from Cache - ', thisCacheName);
        return caches.delete(thisCacheName);
      }
    }));
  }).then(caches.open(cacheName).then(function(cache) {
      return cache.addAll([
          offlineUrl
      ]);
    }))
);
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request)
        .then(function(response) {
            if(response){
                return response
            }else{
                // clone request stream
                // as stream once consumed, can not be used again
                var reqCopy = event.request.clone();

                return fetch(reqCopy, {}) // reqCopy stream consumed
                .then(function(response) {
                    // bad response
                    // response.type !== 'basic' means third party origin request
                    if(!response || response.status !== 200 || response.type !== 'basic') {
                        return response; // response stream consumed
                    }
                    // clone response stream
                    // as stream once consumed, can not be used again
                    var resCopy = response.clone();
                    // ================== IN BACKGROUND ===================== //
                    // add response to cache and return response
                    caches.open(cacheName)
                    .then(function(cache) {
                        return cache.put(reqCopy, resCopy); // reqCopy, resCopy streams consumed
                    });
                    // ====================================================== //
                    return response; // response stream consumed
                }).catch(error => {
                  // Return the offline page
                  return caches.match(offlineUrl);
              });
            }
        })
    );
});
