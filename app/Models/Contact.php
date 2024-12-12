<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasUuids, SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'contact_id',
        'accepted'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function contact()
    {
        return $this->belongsTo(User::class);
    }
}
