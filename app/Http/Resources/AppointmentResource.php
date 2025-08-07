<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'patient'          => [
                'id'    => optional($this->patient)->id,
                'name'  => optional(optional($this->patient)->user)->name,
                'email' => optional(optional($this->patient)->user)->email,
            ],
            'doctor'           => [
                'id'             => optional($this->doctor)->id,
                'name'           => optional(optional($this->doctor)->user)->name,
                'specialization' => optional($this->doctor)->specialization,
            ],
            'appointment_time' => $this->appointment_time,
            'notes'            => $this->notes,
            'status'           => $this->status,
            'created_at'       => optional($this->created_at)->toDateTimeString(),
            'updated_at'       => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}
