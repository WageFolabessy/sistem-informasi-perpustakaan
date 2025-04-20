<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Log;

class FcmController extends Controller
{
    public function storeToken(Request $request)
    {
        $request->validate(['token' => 'required|string']);
        try {
            DeviceToken::updateOrCreate(
                ['device_token' => $request->token]
                // Jika pakai user_id, tambahkan: , ['user_id' => auth()->id()]
            );
            return response()->json(['message' => 'Token stored successfully.'], 200);
        } catch (\Exception $e) {
            Log::error('Error storing FCM token: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to store token.'], 500);
        }
    }
}