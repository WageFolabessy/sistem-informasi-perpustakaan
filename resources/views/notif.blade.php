<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Penting jika route /store-fcm-token Anda ada di routes/web.php --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tes Notifikasi FCM Laravel</title>

    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        button {
            padding: 10px 15px;
            font-size: 1em;
            cursor: pointer;
        }

        #status {
            margin-top: 15px;
            font-style: italic;
            color: #555;
        }
    </style>
</head>

<body>
    <div id="app">
        <h1>Tes Notifikasi Firebase Cloud Messaging</h1>

        <p>Selamat datang! Klik tombol di bawah ini untuk mengizinkan notifikasi dari aplikasi ini dan mendaftarkan
            browser Anda.</p>

        {{-- Tombol untuk memicu permintaan izin --}}
        <button id="enable-notifications-button">Aktifkan Notifikasi Saya</button>

        {{-- Area untuk menampilkan status proses --}}
        <div id="status">Silakan klik tombol di atas.</div>
    </div>

    <script src="https://www.gstatic.com/firebasejs/9.15.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.15.0/firebase-messaging-compat.js"></script>

    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyBsertCHkfWi2EjNmJcgVVLyfNKQ4nosnw",
            authDomain: "sistem-informasi-perpust-43e3d.firebaseapp.com",
            projectId: "sistem-informasi-perpust-43e3d",
            storageBucket: "sistem-informasi-perpust-43e3d.firebasestorage.app",
            messagingSenderId: "695734632799",
            appId: "1:695734632799:web:3c4825f2b7b101ad1876ea"
        };

        const VAPID_KEY =
            "BEv8TEjmrJIB8YHc--O7Y0jUOiHhCnRDe8E6zCk6kUw03otnjT6oLdlGrGQ2Jslgdt6A8lPH7MpvL3QYBDbKZR0"; // <-- Ganti ini

        // Inisialisasi Firebase
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        const statusElement = document.getElementById('status');
        const enableNotificationsButton = document.getElementById('enable-notifications-button');

        // Fungsi Meminta Izin dan Mendapatkan Token
        function requestPermissionAndGetToken() {
            statusElement.textContent = 'Meminta izin notifikasi...';
            console.log('Meminta izin notifikasi...');

            Notification.requestPermission().then((permission) => {
                if (permission === 'granted') {
                    statusElement.textContent = 'Izin diberikan. Mengambil token FCM...';
                    console.log('Izin notifikasi diberikan.');

                    messaging.getToken({
                            vapidKey: VAPID_KEY
                        })
                        .then((currentToken) => {
                            if (currentToken) {
                                console.log('FCM Token berhasil didapatkan:', currentToken);
                                statusElement.textContent = 'Token didapatkan! Mengirim ke server...';
                                sendTokenToServer(currentToken);
                            } else {
                                console.log('Gagal mendapatkan token FCM.');
                                statusElement.textContent =
                                    'Gagal mendapatkan token. Pastikan tidak ada masalah dengan Service Worker atau izin.';
                            }
                        }).catch((err) => {
                            console.error('Error saat mengambil token FCM: ', err);
                            statusElement.textContent = 'Error saat mengambil token: ' + err.message;
                        });
                } else {
                    console.log('Izin notifikasi ditolak oleh pengguna.');
                    statusElement.textContent = 'Izin notifikasi ditolak.';
                }
            }).catch((err) => {
                console.error('Error saat meminta izin notifikasi: ', err);
                statusElement.textContent = 'Error saat meminta izin: ' + err.message;
            });
        }

        // Fungsi Mengirim Token ke Server Laravel
        function sendTokenToServer(token) {
            // Menggunakan helper url() dari Blade untuk path yang benar
            const url = '{{ url('/store-fcm-token') }}'; // Pastikan route ini benar (api.php atau web.php)
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken // Kirim CSRF token jika route ada di web.php
                    },
                    body: JSON.stringify({
                        token: token
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        statusElement.textContent = `Error ${response.status} saat mengirim token.`;
                        console.error('Error response server:', response.status, response.statusText);
                        // Bisa ditambahkan parsing error dari response body jika ada
                        // response.json().then(errData => console.error('Server error details:', errData));
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Respon server setelah simpan token:', data);
                    statusElement.textContent = 'Token berhasil disimpan di server!';
                    // Nonaktifkan tombol setelah berhasil
                    if (enableNotificationsButton) {
                        enableNotificationsButton.disabled = true;
                        enableNotificationsButton.textContent = 'Notifikasi Aktif';
                    }
                })
                .catch((error) => {
                    console.error('Error saat fetch ke server:', error);
                    statusElement.textContent = 'Gagal mengirim token ke server. Cek console & network tab.';
                });
        }

        // Tambahkan event listener ke tombol
        if (enableNotificationsButton) {
            enableNotificationsButton.addEventListener('click', requestPermissionAndGetToken);
        } else {
            console.error("Error: Tombol dengan ID 'enable-notifications-button' tidak ditemukan.");
            statusElement.textContent = 'Error: Tombol aktivasi tidak ditemukan.';
        }

        // Handler untuk Pesan Foreground (saat tab aktif)
        messaging.onMessage((payload) => {
            console.log('Pesan FCM diterima (Foreground):', payload);

            // Tampilkan notifikasi secara manual
            const notificationTitle = payload.notification?.title || 'Notifikasi';
            const notificationBody = payload.notification?.body || 'Anda punya pesan baru.';
            alert(`Notifikasi Masuk:\n${notificationTitle}\n${notificationBody}`);
            statusElement.textContent = `Notifikasi baru diterima: ${notificationTitle}`;

            // Opsional: Gunakan Notification API jika ingin popup sistem
            // const notification = new Notification(notificationTitle, {
            //    body: notificationBody,
            //    icon: payload.notification?.icon // Jika ada icon dari server
            // });
        });
    </script>
</body>

</html>
