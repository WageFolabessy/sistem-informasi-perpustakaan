<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification; // Alias agar tidak bentrok
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function sendTestNotification() // Bisa ditambahkan Request $request jika perlu input
    {
        $tokens = DeviceToken::pluck('device_token')->toArray();
        if (empty($tokens)) {
            Log::info('Tidak ada token perangkat terdaftar untuk dikirimi notifikasi.');
            return response()->json(['message' => 'No devices registered.'], 404);
        }

        $messaging = Firebase::messaging();
        $notification = FirebaseNotification::create('Judul Notifikasi Tes', 'Ini isi pesan dari Laravel!');
        $message = CloudMessage::new()
            ->withNotification($notification)
            ->withData(['info' => 'Data Tambahan']); // Opsional data payload

        try {
            $report = $messaging->sendMulticast($message, $tokens);
            $successCount = $report->successes()->count();
            $failureCount = $report->failures()->count();
            Log::info("Laporan Pengiriman FCM: Sukses=$successCount, Gagal=$failureCount");

            // Handle token gagal (opsional tapi bagus)
            if ($report->hasFailures()) {
                foreach ($report->failures()->getItems() as $failure) {
                    Log::warning("Gagal kirim ke token: " . $failure->target()->value() . " | Alasan: " . $failure->error()->getMessage());
                    // Hapus token yang tidak valid
                    if (in_array($failure->error()->getCode(), ['UNREGISTERED', 'INVALID_ARGUMENT'])) {
                        DeviceToken::where('device_token', $failure->target()->value())->delete();
                        Log::info("Token {$failure->target()->value()} dihapus karena tidak valid.");
                    }
                }
            }
            return response()->json(['message' => "Percobaan pengiriman selesai. Sukses: $successCount, Gagal: $failureCount"]);
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
            Log::error('Error FCM Messaging: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengirim notifikasi (Messaging).'], 500);
        } catch (\Throwable $e) {
            Log::error('Error Umum Kirim Notifikasi: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengirim notifikasi (Umum).'], 500);
        }
    }
}
