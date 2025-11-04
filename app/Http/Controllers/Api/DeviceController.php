<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DeviceController extends Controller
{
    /**
     * Register a device token
     */
    public function registerDevice(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'nullable|integer',
            'device_token' => 'required|string|max:500',
            'platform' => 'nullable|string|in:ios,android'
        ]);

        try {
            $deviceToken = DeviceToken::registerToken(
                $request->user_id,
                $request->device_token,
                $request->platform
            );

            return response()->json([
                'success' => true,
                'message' => 'Device token registered successfully',
                'data' => $deviceToken
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register device token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all registered device tokens (for debugging/admin purposes)
     */
    public function getDeviceTokens(): JsonResponse
    {
        $tokens = DeviceToken::with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tokens
        ]);
    }
}
