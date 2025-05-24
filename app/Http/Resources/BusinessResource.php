<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BusinessResource extends JsonResource
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
            'description' => $this->description,
            'phone' => $this->phone,
            'email' => $this->email,
            'active' => $this->active,
            'logo_image' => $this->getLogoImageUrl(),
            'cover_image' => $this->getCoverImageUrl(),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
        ];
    }

    public function getLogoImageUrl(): string
    {
        $disk = config('app.env') === 'local' ? 'public' : 's3';

        /** @var \Illuminate\Filesystem\FilesystemManager $storage */
        $storage = Storage::disk($disk);

        return $storage->url($this->logo_image);
    }

    public function getCoverImageUrl(): string
    {
        $disk = config('app.env') === 'local' ? 'public' : 's3';

        /** @var \Illuminate\Filesystem\FilesystemManager $storage */
        $storage = Storage::disk($disk);

        return $storage->url($this->cover_image);
    }
}
