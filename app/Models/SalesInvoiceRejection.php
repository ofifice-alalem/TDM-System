<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesInvoiceRejection extends Model
{
    protected $fillable = [
        'sales_invoice_id',
        'rejected_by',
        'rejection_reason',
        'rejected_at'
    ];

    protected $casts = [
        'rejected_at' => 'datetime'
    ];

    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
