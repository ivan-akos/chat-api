<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    
    //@use HasFactory<\Database\Factories\MessageFactory> 
    // use HasFactory;
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'content',
        'is_read'
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}