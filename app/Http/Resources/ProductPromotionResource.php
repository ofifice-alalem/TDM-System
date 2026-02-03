<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPromotionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product?->name,
            'min_quantity' => $this->min_quantity,
            'free_quantity' => $this->free_quantity,
            'promotion_text' => "اشتري {$this->min_quantity} واحصل على {$this->free_quantity} مجاناً",
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
