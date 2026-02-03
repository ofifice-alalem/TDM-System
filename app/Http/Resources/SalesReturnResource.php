<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesReturnResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'return_number' => $this->return_number,
            'sales_invoice_id' => $this->sales_invoice_id,
            'sales_invoice_number' => $this->salesInvoice?->invoice_number,
            'store_id' => $this->store_id,
            'store_name' => $this->store?->name,
            'marketer_id' => $this->marketer_id,
            'marketer_name' => $this->marketer?->full_name,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'keeper_id' => $this->keeper_id,
            'keeper_name' => $this->keeper?->full_name,
            'stamped_image' => $this->stamped_image ? (filter_var($this->stamped_image, FILTER_VALIDATE_URL) ? $this->stamped_image : asset('storage/' . $this->stamped_image)) : null,
            'confirmed_at' => $this->confirmed_at?->format('Y-m-d H:i:s'),
            'notes' => $this->notes,
            'items' => $this->whenLoaded('items', function() {
                return $this->items->map(function($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product?->name,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'subtotal' => $item->quantity * $item->unit_price
                    ];
                });
            }),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    private function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'قيد الانتظار',
            'approved' => 'موثق',
            'rejected' => 'مرفوض',
            'cancelled' => 'ملغي',
            default => $this->status
        };
    }
}
