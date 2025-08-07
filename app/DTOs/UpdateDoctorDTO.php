<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class UpdateDoctorDTO
{
    public string $name;
    public string $email;
    public ?string $specialization;
    public ?string $phone;
    public ?string $address;

    public function __construct(array $data)
    {
        $this->name = trim($data['name'] ?? '');
        $this->email = strtolower(trim($data['email'] ?? ''));

        $this->specialization = isset($data['specialization'])
            ? trim($data['specialization'])
            : null;

        $this->phone = isset($data['phone'])
            ? trim($data['phone'])
            : null;

        $this->address = isset($data['address'])
            ? trim($data['address'])
            : null;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->only([
                'name',
                'email',
                'specialization',
                'phone',
                'address',
            ])
        );
    }
}
