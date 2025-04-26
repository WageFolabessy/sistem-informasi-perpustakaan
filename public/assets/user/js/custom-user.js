import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
import {
    getMessaging,
    getToken,
    isSupported as isFcmSupported,
} from "https://www.gstatic.com/firebasejs/11.6.1/firebase-messaging.js";

const firebaseConfig = {
    apiKey: "AIzaSyBsertCHkfWi2EjNmJcgVVLyfNKQ4nosnw",
    authDomain: "sistem-informasi-perpust-43e3d.firebaseapp.com",
    projectId: "sistem-informasi-perpust-43e3d",
    storageBucket: "sistem-informasi-perpust-43e3d.appspot.com",
    messagingSenderId: "695734632799",
    appId: "1:695734632799:web:3c4825f2b7b101ad1876ea",
};

let app;
let messaging;
let isFcmInitialized = false;
const vapidKey =
    "BEv8TEjmrJIB8YHc--O7Y0jUOiHhCnRDe8E6zCk6kUw03otnjT6oLdlGrGQ2Jslgdt6A8lPH7MpvL3QYBDbKZR0";

try {
    app = initializeApp(firebaseConfig);
    console.log("[FCM] Firebase App Initialized (Module).");

    isFcmSupported()
        .then((isSupported) => {
            if (isSupported) {
                messaging = getMessaging(app);
                console.log("[FCM] Firebase Messaging Initialized (Module).");
                isFcmInitialized = true;

                if (Notification.permission === "granted") {
                    console.log(
                        "[FCM] Permission already granted. Getting token..."
                    );
                    getAndSendFcmToken();
                } else {
                    console.log("[FCM] Permission not granted yet.");
                }
            } else {
                console.warn(
                    "[FCM] Push messaging is not supported in this browser."
                );
                disableFcmFeatures();
            }
        })
        .catch((err) => {
            console.error("[FCM] Error checking FCM support:", err);
            disableFcmFeatures();
        });
} catch (e) {
    console.error("[FCM] Firebase Initialization Error:", e);
    disableFcmFeatures();
}

function disableFcmFeatures() {
    const enableButton = document.getElementById("enable-fcm-button");
    if (enableButton) {
        enableButton.disabled = true;
        enableButton.innerText = "Notifikasi Tidak Didukung";
        enableButton.title = "Browser ini tidak mendukung push notification.";
    }
}

function requestNotificationPermission() {
    if (!isFcmInitialized || !messaging) {
        console.error(
            "[FCM] Cannot request permission, FCM not initialized or not supported."
        );
        alert(
            "Fitur notifikasi tidak didukung atau gagal diinisialisasi di browser ini."
        );
        return;
    }
    console.log("[FCM] Requesting notification permission...");
    Notification.requestPermission()
        .then((permission) => {
            const enableButton = document.getElementById("enable-fcm-button");
            if (permission === "granted") {
                console.log("[FCM] Notification permission granted.");
                getAndSendFcmToken();
                if (enableButton) {
                    enableButton.disabled = true;
                    enableButton.innerText = "Notifikasi Aktif";
                    enableButton.title = "Notifikasi sudah diaktifkan.";
                }
            } else {
                console.log("[FCM] Unable to get permission to notify.");
                alert("Anda memilih untuk tidak mengizinkan notifikasi.");
                if (enableButton) {
                    enableButton.disabled = false;
                    enableButton.innerText = "Aktifkan Notifikasi Browser";
                }
            }
        })
        .catch((err) => {
            console.error("[FCM] Error requesting permission:", err);
            alert("Gagal meminta izin notifikasi.");
        });
}

async function getAndSendFcmToken() {
    if (!isFcmInitialized || !messaging) {
        console.error(
            "[FCM] Messaging service not available or not initialized."
        );
        return;
    }

    try {
        console.log("[FCM] Attempting to get token...");
        const currentToken = await getToken(messaging, { vapidKey: vapidKey });
        if (currentToken) {
            console.log("[FCM] Token obtained:", currentToken);
            sendTokenToServer(currentToken);
        } else {
            console.warn(
                "[FCM] No registration token available. Request permission first."
            );
        }
    } catch (err) {
        console.error("[FCM] An error occurred while retrieving token: ", err);
        let errorMessage = "Gagal mendapatkan token notifikasi. ";
        if (
            err.code === "messaging/notifications-blocked" ||
            err.code === "messaging/permission-blocked"
        ) {
            errorMessage +=
                "Pastikan Anda mengizinkan notifikasi untuk situs ini di pengaturan browser Anda.";
        } else if (
            err.code === "messaging/failed-service-worker-registration"
        ) {
            errorMessage +=
                "Gagal mendaftarkan service worker. Pastikan firebase-messaging-sw.js ada di root public dan bisa diakses.";
        } else {
            errorMessage += "Terjadi kesalahan tak terduga.";
        }
        console.error(errorMessage);
    }
}

function sendTokenToServer(token) {
    if (typeof window.fcmTokenStoreUrl === "undefined") {
        console.error(
            "[FCM] Backend URL (window.fcmTokenStoreUrl) is not defined."
        );
        return;
    }
    const url = window.fcmTokenStoreUrl;
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    if (!csrfToken) {
        console.error("[FCM] CSRF token not found.");
        return;
    }

    console.log(`[FCM] Sending token to: ${url}`);
    fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
        },
        body: JSON.stringify({ fcm_token: token }),
    })
        .then((response) => {
            if (!response.ok) {
                return response
                    .json()
                    .then((errData) => {
                        throw new Error(
                            errData.message ||
                                `Server responded with status ${response.status}`
                        );
                    })
                    .catch(() => {
                        throw new Error(
                            `Server responded with status ${response.status}`
                        );
                    });
            }
            return response.json();
        })
        .then((data) => {
            console.log(
                "[FCM] Token successfully sent to server:",
                data.message
            );
            const enableButton = document.getElementById("enable-fcm-button");
            if (enableButton && !enableButton.disabled) {
                enableButton.disabled = true;
                enableButton.innerText = "Notifikasi Aktif";
                enableButton.title = "Notifikasi sudah diaktifkan.";
            }
        })
        .catch((error) => {
            console.error("[FCM] Error sending token to server:", error);
        });
}

document.addEventListener("DOMContentLoaded", () => {
    const enableButton = document.getElementById("enable-fcm-button");
    if (enableButton) {
        isFcmSupported()
            .then((isSupported) => {
                if (!isSupported) {
                    enableButton.disabled = true;
                    enableButton.innerText = "Notifikasi Tidak Didukung";
                    enableButton.title =
                        "Browser ini tidak mendukung push notification.";
                    return;
                }
                if (Notification.permission === "granted") {
                    enableButton.disabled = true;
                    enableButton.innerText = "Notifikasi Sudah Aktif";
                } else if (Notification.permission === "default") {
                    console.log(
                        "[FCM] Permission is default. Requesting permission automatically..."
                    );
                    requestNotificationPermission();
                } else if (Notification.permission === "denied") {
                    enableButton.disabled = true;
                    enableButton.innerText = "Notifikasi Diblokir";
                    enableButton.title =
                        "Izin notifikasi diblokir di pengaturan browser.";
                    requestNotificationPermission();
                } else {
                    enableButton.disabled = false;
                    enableButton.innerText = "Aktifkan Notifikasi Browser";
                    enableButton.addEventListener(
                        "click",
                        requestNotificationPermission
                    );
                }
            })
            .catch((err) => {
                console.error(
                    "[FCM] Error checking FCM support on DOMContentLoaded:",
                    err
                );
                if (enableButton) {
                    enableButton.disabled = true;
                    enableButton.innerText = "Error Cek Notifikasi";
                }
            });
    }
});

// Listener untuk pesan foreground (opsional)
// if (isFcmInitialized && messaging) {
//     onMessage(messaging, (payload) => {
//         console.log(
//             "[FCM] Message received while app is foregrounded: ",
//             payload
//         );
//         alert(
//             `Notifikasi Baru: ${payload.notification?.title}\n${payload.notification?.body}`
//         );
//     });
// }
