let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault(); // Prevent the default install prompt
    deferredPrompt = e; // Save the event for later use
    // Show your custom install button here
});

// Example button click event to show the install prompt
document.getElementById('installButton').addEventListener('click', () => {
    if (deferredPrompt) {
        deferredPrompt.prompt(); // Show the install prompt
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted the A2HS prompt');
            } else {
                console.log('User dismissed the A2HS prompt');
            }
            deferredPrompt = null; // Clear the deferred prompt
        });
    }
});
