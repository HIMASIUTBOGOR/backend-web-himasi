<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegistrationResource extends JsonResource
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
            'fullname' => $this->fullname,
            'nim' => $this->nim,
            'semester' => $this->semester,
            'no_wa' => $this->no_wa,
            'department_name' => $this->department ? $this->department->title : null,
            'department_id' => $this->department_id,
            'reason' => $this->reason,
            'created_at' => $this->created_at,
        ];
    }


}
