<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'employee' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
            ],

            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'total_days' => $this->total_days,

            'reason' => $this->reason,
            'attachment_url' => $this->attachment_path
                ? asset('storage/' . $this->attachment_path)
                : null,

            'status' => $this->status,

            'reviewer' => $this->when($this->reviewer, [
                'id' => $this->reviewer?->id,
                'name' => $this->reviewer?->name,
                'email' => $this->reviewer?->email,
            ]),

            'reviewed_at' => $this->reviewed_at?->toDateTimeString(),
            'rejection_reason' => $this->rejection_reason,

            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
