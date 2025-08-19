
importScripts("https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js");
importScripts(
    "https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js"
);

// Ganti dengan konfigurasi Firebase Anda
const firebaseConfig = {
    apiKey: "AIzaSyDhDOrQPu8CaVG70qzsHy-nMx77By6Qii0",
    authDomain: "kaosta-472.firebaseapp.com",
    projectId: "kaosta-472",
    storageBucket: "kaosta-472.firebasestorage.app",
    messagingSenderId: "72837679305",
    appId: "1:72837679305:web:26c3be62a0a2d65b61a518",
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    self.registration.showNotification(payload.notification.title, {
        body: payload.notification.body,
        icon: "logo.png",
    });
});
