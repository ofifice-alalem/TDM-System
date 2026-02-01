<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseStockLog extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'invoice_type',
        'invoice_id',
        'keeper_id',
        'action',
    ];

    public function keeper(): BelongsTo
    {
        return $this->belongsTo(User::class, 'keeper_id');
    }
}
