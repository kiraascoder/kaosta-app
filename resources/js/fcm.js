import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
import {
    getMessaging,
    getToken,
    onMessage,
} from "https://www.gstatic.com/firebasejs/10.8.0/firebase-messaging.js";

// Konfigurasi Firebase Anda
const firebaseConfig = {
    apiKey: "AIzaSyDhDOrQPu8CaVG70qzsHy-nMx77By6Qii0",
    authDomain: "kaosta-472.firebaseapp.com",
    projectId: "kaosta-472",
    storageBucket: "kaosta-472.firebasestorage.app",
    messagingSenderId: "72837679305",
    appId: "1:72837679305:web:26c3be62a0a2d65b61a518",
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

// ✅ Perbaikan terakhir: Logika yang lebih defensif
async function setupFirebaseMessaging() {
    try {
        let registration = await navigator.serviceWorker.getRegistration(
            "/firebase-messaging-sw.js"
        );

        // Jika Service Worker belum terdaftar, daftarkan sekarang
        if (!registration) {
            registration = await navigator.serviceWorker.register(
                "/firebase-messaging-sw.js"
            );
            console.log("Service Worker didaftarkan.");
        }

        // Tunggu hingga Service Worker benar-benar siap
        await navigator.serviceWorker.ready;
        console.log("Service Worker sudah aktif dan siap.");

        // Meminta izin notifikasi
        const permission = await Notification.requestPermission();
        if (permission === "granted") {
            const currentToken = await getToken(messaging, {
                vapidKey:
                    "BDmfpO4W-0ZQ0j-NYgEsviyPWQhFN7T3lzFlqJJFd17DwUJ_io202GqUNtgpP6cYwt1RpjRkXehCpB3hhlBgRBQ",
            });

            if (currentToken) {
                console.log("Token perangkat:", currentToken);
                await fetch("/save-fcm-token", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                    body: JSON.stringify({ token: currentToken }),
                });
                console.log("Token berhasil disimpan di backend.");
            } else {
                console.warn("Tidak mendapatkan token FCM.");
            }
        } else {
            console.warn("Izin notifikasi ditolak.");
        }
    } catch (err) {
        console.error("Error dalam proses FCM:", err);
    }
}

// Jalankan proses setelah DOM dimuat sepenuhnya
document.addEventListener("DOMContentLoaded", setupFirebaseMessaging);

onMessage(messaging, (payload) => {
    console.log("Pesan diterima saat foreground:", payload);
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: payload.notification.icon || "/apk.png",
    };
    new Notification(notificationTitle, notificationOptions);
});
