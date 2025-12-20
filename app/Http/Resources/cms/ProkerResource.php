<?php

namespace App\Http\Resources\cms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProkerResource extends JsonResource
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
            'departemen_id' => $this->departemen_id,
            'departemen' => $this->departemen->title,
            'photo' => $this->photo ? url('storage/' . $this->photo) : null,
            'title' => $this->title,
            'desc' => $this->desc,
            'action_link' => $this->action_link,
            'is_active' => $this->is_active,
        ];
    }
}
