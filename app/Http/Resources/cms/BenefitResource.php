<?php

namespace App\Http\Resources\cms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BenefitResource extends JsonResource
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
            'photo' => asset('storage/' . $this->photo),
            'title' => $this->title,
            'desc' => $this->desc,
        ];
    }
}
