<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'current_plan' => $this->getPlanName(),
        ];
    }

    private function getPlanName(): ?string
    {
        $subscription = $this->subscriptions()->where('stripe_status', 'active')->first();

        if (! $subscription) {
            return null;
        }

        $plans = [
            config('services.stripe.pro_price_id') => 'pro',
            config('services.stripe.basic_price_id') => 'basic',
        ];

        return $plans[$subscription->stripe_price] ?? null;
    }
}
