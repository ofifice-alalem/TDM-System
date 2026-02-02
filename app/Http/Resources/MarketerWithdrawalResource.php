<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarketerWithdrawalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'marketer_id' => $this->marketer_id,
            'marketer_name' => $this->marketer?->full_name,
            'requested_amount' => $this->requested_amount,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'approved_by' => $this->approved_by,
            'approved_by_name' => $this->approvedBy?->full_name,
            'approved_at' => $this->approved_at?->format('Y-m-d H:i:s'),
            'rejected_by' => $this->rejected_by,
            'rejected_by_name' => $this->rejectedBy?->full_name,
            'rejected_at' => $this->rejected_at?->format('Y-m-d H:i:s'),
            'signed_receipt_image' => $this->signed_receipt_image ? (filter_var($this->signed_receipt_image, FILTER_VALIDATE_URL) ? $this->signed_receipt_image : asset('storage/' . $this->signed_receipt_image)) : null,
            'notes' => $this->notes,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    private function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'قيد الانتظار',
            'approved' => 'معتمد',
            'rejected' => 'مرفوض',
            'cancelled' => 'ملغي',
            default => $this->status
        };
    }
}
