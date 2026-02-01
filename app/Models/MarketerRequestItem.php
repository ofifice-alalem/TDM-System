<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketerRequestItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'request_id',
        'product_id',
        'quantity',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(MarketerRequest::class, 'request_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
