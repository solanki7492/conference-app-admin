<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceToken extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'platform',
        'last_used_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all active device tokens
     */
    public static function getActiveTokens()
    {
        return static::whereNotNull('token')
            ->where('token', '!=', '')
            ->pluck('token')
            ->toArray();
    }

    /**
     * Register or update a device token
     */
    public static function registerToken($userId, $token, $platform = null)
    {
        return static::updateOrCreate(
            ['token' => $token],
            [
                'user_id' => $userId,
                'platform' => $platform,
                'last_used_at' => now(),
            ]
        );
    }
}
