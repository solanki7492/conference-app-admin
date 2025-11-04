<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'message',
        'sent_to',
        'target_tokens',
        'sent_count',
        'success_count',
        'failure_count',
        'sent_by',
    ];

    protected $casts = [
        'target_tokens' => 'array',
    ];

    public function sentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    /**
     * Get the success rate of the notification
     */
    public function getSuccessRateAttribute()
    {
        if ($this->sent_count === 0) {
            return 0;
        }
        
        return round(($this->success_count / $this->sent_count) * 100, 2);
    }

    /**
     * Scope for recent notifications
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
