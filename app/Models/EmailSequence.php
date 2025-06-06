<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TagEmailSequence;

class EmailSequence extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'email_sequences';

    protected $fillable = [
        'workspace_id',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * An email sequence belongs to a workspace
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(\Sendportal\Base\Models\Workspace::class, 'workspace_id');
    }

    /**
     * An email sequence has many sequence emails
     */
    public function emails(): HasMany
    {
        return $this->hasMany(SequenceEmail::class, 'email_sequence_id')->orderBy('send_order', 'asc');
    }

    /**
     * An email sequence has many subscriber sequences
     */
    public function subscriberSequences(): HasMany
    {
        return $this->hasMany(SubscriberSequence::class);
    }

    /**
     * Get active subscriber sequences
     */
    public function activeSubscriberSequences(): HasMany
    {
        return $this->subscriberSequences()->where('status', 'active');
    }

    public function tagMapping(): HasOne
    {
        return $this->hasOne(TagEmailSequence::class);
    }

    /**
     * Get the total number of emails in this sequence
     */
    public function getTotalEmailsAttribute(): int
    {
        return $this->emails()->count();
    }

    /**
     * Get the total number of active subscribers
     */
    public function getActiveSubscribersCountAttribute(): int
    {
        return $this->activeSubscriberSequences()->count();
    }

    /**
     * Add a subscriber to this sequence
     */
    public function addSubscriber($subscriberId)
    {
        // Don't add if already in sequence
        if ($this->subscriberSequences()->where('subscriber_id', $subscriberId)->exists()) {
            return false;
        }

        // Get first email delay
        $firstEmail = $this->emails()->orderBy('send_order')->first();
        $nextSendAt = $firstEmail ? \Carbon\Carbon::now()->addDays($firstEmail->delay_days) : \Carbon\Carbon::now();

        return $this->subscriberSequences()->create([
            'subscriber_id' => $subscriberId,
            'current_step' => 1,
            'started_at' => \Carbon\Carbon::now(),
            'next_send_at' => $nextSendAt,
            'status' => 'active'
        ]);
    }
}