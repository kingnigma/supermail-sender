<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'subject',
        'message',
        'status',
        'sent_count',
        'failed_count',
        'completed_at',
        'contact_group_id',
        'message_template_id',
        'invoice_template_id',
        'user_id',
        'attachment_path'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * Status constants
     */
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    /**
     * Get the user that owns the campaign.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the contact group associated with the campaign.
     */
    public function contactGroup(): BelongsTo
    {
        return $this->belongsTo(ContactGroup::class);
    }

    /**
     * Get the message template associated with the campaign.
     */
    public function messageTemplate(): BelongsTo
    {
        return $this->belongsTo(MessageTemplate::class);
    }

    /**
     * Get the invoice template associated with the campaign.
     */
    public function invoiceTemplate(): BelongsTo
    {
        return $this->belongsTo(InvoiceTemplate::class);
    }

    /**
     * Get all email histories for the campaign.
     */
    public function emailHistories(): HasMany
    {
        return $this->hasMany(EmailHistory::class);
    }

    /**
     * Scope a query to only include campaigns for the current user.
     */
    public function scopeForCurrentUser($query)
    {
        return $query->where('user_id', auth()->id());
    }

    /**
     * Get the formatted created at date.
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('M d, Y H:i');
    }

    /**
     * Get the formatted completed at date.
     */
    public function getFormattedCompletedAtAttribute()
    {
        return $this->completed_at?->format('M d, Y H:i');
    }

    /**
     * Check if campaign is completed.
     */
    public function getIsCompletedAttribute()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if campaign is processing.
     */
    public function getIsProcessingAttribute()
    {
        return $this->status === self::STATUS_PROCESSING;
    }

    /**
     * Check if campaign failed.
     */
    public function getIsFailedAttribute()
    {
        return $this->status === self::STATUS_FAILED;
    }
}