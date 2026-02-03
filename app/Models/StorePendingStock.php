<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorePendingStock extends Model
{
    public $timestamps = false;
    const UPDATED_AT = null;

    protected $table = 'store_pending_stock';

    protected $fillable = [
        'sales_invoice_id',
        'store_id',
        'product_id',
        'quantity'
    ];

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
