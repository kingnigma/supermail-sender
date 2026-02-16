<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'recipient_email',
        'recipient_name',
        'status',
        'error_message',
        'sent_at'
    ];

    protected $dates = ['sent_at'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
