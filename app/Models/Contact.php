<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_group_id',
        'full_name',
        'company_name',
        'company_email'
    ];

    public function group()
    {
        return $this->belongsTo(ContactGroup::class, 'contact_group_id');
    }
}