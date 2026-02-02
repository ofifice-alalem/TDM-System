<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'marketer_id',
        'store_id',
        'total_amount',
        'subtotal',
        'product_discount',
        'invoice_discount_type',
        'invoice_discount_value',
        'invoice_discount_amount',
        'status',
        'keeper_id',
        'stamped_invoice_image',
        'confirmed_at',
        'notes'
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'product_discount' => 'decimal:2',
        'invoice_discount_value' => 'decimal:2',
        'invoice_discount_amount' => 'decimal:2'
    ];

    public function marketer()
    {
        return $this->belongsTo(User::class, 'marketer_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function keeper()
    {
        return $this->belongsTo(User::class, 'keeper_id');
    }

    public function items()
    {
        return $this->hasMany(SalesInvoiceItem::class, 'invoice_id');
    }

    public function rejection()
    {
        return $this->hasOne(SalesInvoiceRejection::class, 'sales_invoice_id');
    }
}
