<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'message' => $this->message,
            'business' => $this->when($this->business, [
                'name' => $this->business?->name,
                'color' => $this->business?->color ?? 'bg-gray-500',
            ]),
            'customer_name' => $this->customer_name,
            'rating' => $this->rating,
            'is_read' => $this->is_read,
            'created_at' => $this->created_at->toISOString(),
            'action_url' => $this->action_url,
        ];
    }
}
