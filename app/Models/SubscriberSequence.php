<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriberSequence extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscriber_id',
        'email_sequence_id',
        'current_step',
        'started_at',
        'next_send_at',
        'completed_at',
        'status',
    ];

    protected $casts = [
        'current_step' => 'integer',
        'started_at' => 'datetime',
        'next_send_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * A subscriber sequence belongs to a subscriber
     */
    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(\Sendportal\Base\Models\Subscriber::class, 'subscriber_id');
    }

    /**
     * A subscriber sequence belongs to an email sequence
     */
    public function emailSequence(): BelongsTo
    {
        return $this->belongsTo(EmailSequence::class);
    }

    /**
     * Check if this sequence is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if this sequence is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}