<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'name'           => optional($this->user)->name,
            'email'          => optional($this->user)->email,
            'specialization' => $this->specialization,
            'phone'          => $this->phone,
            'address'        => $this->address,
            'created_at'     => $this->created_at?->toDateTimeString(),
        ];
    }
}
