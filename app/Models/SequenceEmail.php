<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SequenceEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_sequence_id',
        'template_id',
        'delay_days',
        'send_order',
        'subject',
    ];

    protected $casts = [
        'delay_days' => 'integer',
        'send_order' => 'integer',
    ];

    /**
     * A sequence email belongs to an email sequence
     */
    public function emailSequence(): BelongsTo
    {
        return $this->belongsTo(EmailSequence::class);
    }

    /**
     * A sequence email may use a SendPortal template
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(\Sendportal\Base\Models\Template::class, 'template_id');
    }
}