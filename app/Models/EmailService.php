<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailService extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_type',
        'is_active',
        'credentials'
    ];

    protected $casts = [
        'credentials' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
