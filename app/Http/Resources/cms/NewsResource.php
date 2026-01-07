<?php

namespace App\Http\Resources\cms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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
            'author' => $this->author,
            'published_at' => $this->published_at,
            'category_id' => $this->category_id,
            'category' => $this->category->value,
            'is_active' => $this->is_active,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
