<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarketerRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'marketer_id' => $this->marketer_id,
            'marketer_name' => $this->marketer->full_name,
            'status' => $this->status,
            'notes' => $this->notes,
            'approved_by' => $this->approved_by,
            'approved_by_name' => $this->approvedBy?->full_name,
            'approved_at' => $this->approved_at?->format('Y-m-d H:i:s'),
            'rejected_by' => $this->rejected_by,
            'rejected_by_name' => $this->rejectedBy?->full_name,
            'rejected_at' => $this->rejected_at?->format('Y-m-d H:i:s'),
            'documented_by' => $this->documented_by,
            'documented_by_name' => $this->documentedBy?->full_name,
            'documented_at' => $this->documented_at?->format('Y-m-d H:i:s'),
            'stamped_image' => $this->stamped_image,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'items' => MarketerRequestItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
