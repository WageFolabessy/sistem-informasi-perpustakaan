importScripts(
    "https://www.gstatic.com/firebasejs/11.6.1/firebase-app-compat.js"
);
importScripts(
    "https://www.gstatic.com/firebasejs/11.6.1/firebase-messaging-compat.js"
);

console.log("[SW] Service Worker Compat Berjalan!");

const firebaseConfig = {
    apiKey: "AIzaSyBsertCHkfWi2EjNmJcgVVLyfNKQ4nosnw",
    authDomain: "sistem-informasi-perpust-43e3d.firebaseapp.com",
    projectId: "sistem-informasi-perpust-43e3d",
    storageBucket: "sistem-informasi-perpust-43e3d.appspot.com",
    messagingSenderId: "695734632799",
    appId: "1:695734632799:web:3c4825f2b7b101ad1876ea",
};

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    console.log("[SW] Pesan background diterima (Compat):", payload);

    const notificationTitle =
        payload.notification?.title ??
        payload.data?.title ??
        "Notifikasi SIMPerpus";
    const notificationOptions = {
        body:
            payload.notification?.body ??
            payload.data?.body ??
            "Anda memiliki pemberitahuan baru.",
        icon:
            payload.notification?.icon ??
            payload.data?.icon ??
            "/assets/images/logo.png",
        data: payload.data ?? {},
    };

    self.registration
        .showNotification(notificationTitle, notificationOptions)
        .catch((err) =>
            console.error("[SW] Error showing notification: ", err)
        );
});

console.log("Firebase Messaging Service Worker (Compat) Initialized");

self.addEventListener("notificationclick", function (event) {
    console.log("[SW] On notification click: ", event.notification);
    event.notification.close();

    const targetUrl = event.notification.data?.click_action || "/riwayat-pinjam";

    event.waitUntil(
        clients
            .matchAll({
                type: "window",
                includeUncontrolled: true,
            })
            .then(function (clientList) {
                for (var i = 0; i < clientList.length; i++) {
                    var client = clientList[i];
                    let clientUrl = new URL(client.url);
                    let targetPath = new URL(targetUrl, self.location.origin)
                        .pathname;
                    if (
                        clientUrl.pathname === targetPath &&
                        "focus" in client
                    ) {
                        console.log(
                            "[SW] Focusing existing client for:",
                            targetPath
                        );
                        return client.focus();
                    }
                }
                if (clients.openWindow) {
                    console.log("[SW] Opening new window for:", targetUrl);
                    return clients.openWindow(targetUrl);
                }
            })
    );
});
