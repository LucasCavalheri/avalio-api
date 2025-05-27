<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $plans = [
            config('services.stripe.pro_price_id') => 'pro',
            config('services.stripe.basic_price_id') => 'basic',
        ];

        return [
            'id' => $this->id,
            'type' => $plans[$this->stripe_price] ?? null,
            'status' => $this->stripe_status,
            'created_at' => $this->created_at,
            'ends_at' => $this->ends_at,
            'canceled_at' => $this->canceled_at,
        ];
    }
}
