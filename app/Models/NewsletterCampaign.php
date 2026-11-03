<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterCampaign extends Model
{
    protected $fillable = [
        'title',
        'subject',
        'content',
        'frequency',
        'next_send_at',
        'last_sent_at',
        'is_active',
    ];

    protected $casts = [
        'next_send_at' => 'datetime',
        'last_sent_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
