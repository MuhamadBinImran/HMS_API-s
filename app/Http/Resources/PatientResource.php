<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'user_id'   => $this->user_id,
            'name'      => optional($this->user)->name,
            'email'     => optional($this->user)->email,
            'gender'    => $this->gender,
            'dob'       => $this->dob,
            'address'   => $this->address,
            'phone'     => $this->phone,
            'created_at'=> $this->created_at?->toDateTimeString(),
        ];
    }
}
