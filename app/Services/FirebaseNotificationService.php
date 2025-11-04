<?php

namespace App\Services;

use App\Models\DeviceToken;
use App\Models\Notification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class FirebaseNotificationService
{
    private $messaging;

    public function __construct()
    {
        // Initialize Firebase
        $factory = (new Factory)
            ->withServiceAccount(config('firebase.credentials'));
        
        $this->messaging = $factory->createMessaging();
    }

    /**
     * Send notification to all devices
     */
    public function sendToAll(string $title, string $message, ?int $sentBy = null): array
    {
        $tokens = DeviceToken::getActiveTokens();
        
        if (empty($tokens)) {
            return [
                'success' => false,
                'message' => 'No device tokens found',
                'sent_count' => 0,
                'success_count' => 0,
                'failure_count' => 0,
            ];
        }

        return $this->sendToTokens($tokens, $title, $message, 'all', $sentBy);
    }

    /**
     * Send notification to specific tokens
     */
    public function sendToSpecificTokens(array $tokens, string $title, string $message, ?int $sentBy = null): array
    {
        return $this->sendToTokens($tokens, $title, $message, 'specific', $sentBy);
    }

    /**
     * Send notification to multiple tokens
     */
    private function sendToTokens(array $tokens, string $title, string $message, string $sentTo, ?int $sentBy = null): array
    {
        $successCount = 0;
        $failureCount = 0;
        $sentCount = count($tokens);

        // Create notification record
        $notification = Notification::create([
            'title' => $title,
            'message' => $message,
            'sent_to' => $sentTo,
            'target_tokens' => $sentTo === 'specific' ? $tokens : null,
            'sent_count' => $sentCount,
            'sent_by' => $sentBy,
        ]);

        // Split tokens into chunks for batch processing (FCM supports up to 500 tokens per request)
        $tokenChunks = array_chunk($tokens, 500);

        foreach ($tokenChunks as $tokenChunk) {
            try {
                $firebaseNotification = FirebaseNotification::create($title, $message);
                
                $message = CloudMessage::new()
                    ->withNotification($firebaseNotification);

                $report = $this->messaging->sendMulticast($message, $tokenChunk);

                $successCount += $report->successes()->count();
                $failureCount += $report->failures()->count();

                // Handle failed tokens - you might want to remove invalid tokens
                foreach ($report->failures()->getItems() as $failure) {
                    $failedToken = $tokenChunk[$failure->index()];
                    
                    // If token is invalid or unregistered, remove it
                    if (in_array($failure->error()->getCode(), ['invalid-registration-token', 'registration-token-not-registered'])) {
                        DeviceToken::where('token', $failedToken)->delete();
                    }
                }

            } catch (\Exception $e) {
                $failureCount += count($tokenChunk);
                \Log::error('Firebase notification failed: ' . $e->getMessage());
            }
        }

        // Update notification record with results
        $notification->update([
            'success_count' => $successCount,
            'failure_count' => $failureCount,
        ]);

        return [
            'success' => $successCount > 0,
            'message' => "Notification sent to {$successCount} devices successfully",
            'notification_id' => $notification->id,
            'sent_count' => $sentCount,
            'success_count' => $successCount,
            'failure_count' => $failureCount,
        ];
    }

    /**
     * Test Firebase configuration
     */
    public function testConnection(): bool
    {
        try {
            // Try to create a test message to validate configuration
            $testNotification = FirebaseNotification::create('Test', 'Test message');
            $testMessage = CloudMessage::new()->withNotification($testNotification);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Firebase connection test failed: ' . $e->getMessage());
            return false;
        }
    }
}