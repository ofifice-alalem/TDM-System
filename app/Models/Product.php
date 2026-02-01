<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'barcode',
        'description',
        'current_price',
        'is_active',
    ];

    protected $casts = [
        'current_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the main stock for the product.
     */
    public function mainStock(): HasOne
    {
        return $this->hasOne(MainStock::class);
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}