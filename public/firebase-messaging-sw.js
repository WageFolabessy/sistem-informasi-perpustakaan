// Import Firebase SDK scripts (sesuaikan versi jika perlu)
importScripts(
    "https://www.gstatic.com/firebasejs/9.15.0/firebase-app-compat.js"
);
importScripts(
    "https://www.gstatic.com/firebasejs/9.15.0/firebase-messaging-compat.js"
);

console.log("[SW] Script Awal Berjalan!");

// !!! GANTI DENGAN KONFIGURASI FIREBASE ANDA DARI LANGKAH 1.2 !!!
const firebaseConfig = {
    apiKey: "AIzaSyBsertCHkfWi2EjNmJcgVVLyfNKQ4nosnw",
    authDomain: "sistem-informasi-perpust-43e3d.firebaseapp.com",
    projectId: "sistem-informasi-perpust-43e3d",
    storageBucket: "sistem-informasi-perpust-43e3d.firebasestorage.app",
    messagingSenderId: "695734632799",
    appId: "1:695734632799:web:3c4825f2b7b101ad1876ea",
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

// Opsional: Handle notifikasi saat aplikasi di background
// Komentari atau hapus bagian self.addEventListener('push', ...);
// Aktifkan kembali atau tulis ulang bagian ini:
messaging.onBackgroundMessage((payload) => {
    console.log("[SW] Pesan background diterima (via Firebase):", payload);
    const notificationTitle = payload.notification?.title || "Notifikasi Baru";
    const notificationOptions = {
        body: payload.notification?.body || "Anda dapat pesan baru.",
        icon: payload.notification?.icon || "/images/logo.png", // Sesuaikan path icon default
        // Anda juga bisa menggunakan payload.data di sini
    };
    self.registration.showNotification(notificationTitle, notificationOptions);
});
