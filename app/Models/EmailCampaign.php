<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'template_id',
        'contact_list',
        'total_recipients',
        'sent_count',
        'failed_count',
        'status',
        'status_color',
        'subject',
        'sent_at'
    ];

    protected $dates = ['sent_at'];

    public function template()
    {
        return $this->belongsTo(EmailTemplate::class);
    }

    public function getSuccessRateAttribute()
    {
        if ($this->total_recipients === 0) return 0;
        return round(($this->sent_count / $this->total_recipients) * 100);
    }
}