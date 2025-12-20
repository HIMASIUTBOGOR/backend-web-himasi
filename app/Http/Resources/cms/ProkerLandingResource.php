<?php

namespace App\Http\Resources\cms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProkerLandingResource extends JsonResource
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
            'departemen' => $this->title,
            'prokers' => ProkerResource::collection($this->prokers->where('is_active', true)),
        ];
    }
}
