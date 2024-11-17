// Simple Service Worker to only enable PWA install functionality without caching

self.addEventListener("install", function(event) {
    // Automatically activate the service worker after install
    console.log("Service Worker installed");
    self.skipWaiting();
});

self.addEventListener("activate", function(event) {
    console.log("Service Worker activated");
    // Ensure the new service worker takes over immediately
    event.waitUntil(self.clients.claim());
});

self.addEventListener("fetch", function(event) {
    // Do nothing with fetch requests, no caching
    console.log("Fetch event for:", event.request.url);
});
