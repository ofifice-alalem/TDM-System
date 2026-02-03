<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceDiscountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'min_amount' => $this->min_amount,
            'discount_type' => $this->discount_type,
            'discount_type_label' => $this->discount_type === 'percentage' ? 'نسبة مئوية' : 'مبلغ ثابت',
            'discount_percentage' => $this->discount_percentage,
            'discount_amount' => $this->discount_amount,
            'discount_display' => $this->discount_type === 'percentage' 
                ? $this->discount_percentage . '%' 
                : $this->discount_amount . ' دينار',
            'start_date' => $this->start_date->format('Y-m-d'),
            'end_date' => $this->end_date->format('Y-m-d'),
            'is_active' => $this->is_active,
            'status_label' => $this->is_active ? 'مفعل' : 'معطل',
            'created_by' => $this->created_by,
            'creator_name' => $this->creator?->full_name,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
