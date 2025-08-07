<?php

namespace App\DTOs;

use App\Http\Requests\DoctorRequest;

class DoctorDTO
{
    public string $name;
    public string $email;
    public ?string $password; // Nullable for update operations
    public string $specialization;
    public string $phone;
    public string $address;

    private function __construct() {}

    public static function fromRequest(DoctorRequest $request): self
    {
        $dto = new self();
        $dto->name = $request->input('name');
        $dto->email = $request->input('email');
        $dto->password = $request->filled('password') ? $request->input('password') : null;
        $dto->specialization = $request->input('specialization');
        $dto->phone = $request->input('phone');
        $dto->address = $request->input('address');
        return $dto;
    }
}
