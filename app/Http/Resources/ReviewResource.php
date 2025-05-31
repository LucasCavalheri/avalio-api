<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $disk = config('app.env') === 'local' ? 'public' : 's3';
        $storage = Storage::disk($disk);

        return [
            'id' => $this->id,
            'name' => $this->name ?? 'Anônimo',
            'rating' => $this->rating,
            'comment' => $this->comment,
            'response' => $this->response,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'business' => new BusinessResource($this->whenLoaded('business')),
        ];
    }
}
