<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseStockLog extends Model
{
    protected $table = 'warehouse_stock_logs';
    public $timestamps = false;
    const UPDATED_AT = null;

    protected $fillable = [
        'invoice_type',
        'invoice_id',
        'keeper_id',
        'action'
    ];

    public function keeper()
    {
        return $this->belongsTo(User::class, 'keeper_id');
    }
}
