<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'barcode' => $this->barcode,
            'description' => $this->description,
            'current_price' => $this->current_price,
            'is_active' => $this->is_active,
            'stock' => $this->whenLoaded('mainStock', function () {
                return [
                    'quantity' => $this->mainStock->quantity ?? 0,
                    'updated_at' => $this->mainStock->updated_at ?? null,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
