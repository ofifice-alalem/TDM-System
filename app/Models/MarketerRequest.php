<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketerRequest extends Model
{
    protected $fillable = [
        'invoice_number',
        'marketer_id',
        'status',
        'notes',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'documented_by',
        'documented_at',
        'stamped_image',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'documented_at' => 'datetime',
    ];

    public function marketer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marketer_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function documentedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'documented_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(MarketerRequestItem::class, 'request_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeDocumented($query)
    {
        return $query->where('status', 'documented');
    }
}
