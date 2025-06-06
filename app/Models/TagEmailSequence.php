<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TagEmailSequence extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag_id',
        'email_sequence_id',
    ];

    public function tag(): BelongsTo
    {
        return $this->belongsTo(\Sendportal\Base\Models\Tag::class);
    }

    public function emailSequence(): BelongsTo
    {
        return $this->belongsTo(EmailSequence::class);
    }
}
