<?php

namespace App\DTOs;

use App\Http\Requests\UpdatePatientRequest;

class UpdatePatientDTO
{
    public ?string $name;
    public ?string $email;
    public ?string $gender;
    public ?string $dob;
    public ?string $address;
    public ?string $phone;
    public ?string $password;

    public function __construct(
        ?string $name = null,
        ?string $email = null,
        ?string $gender = null,
        ?string $dob = null,
        ?string $address = null,
        ?string $phone = null,
        ?string $password = null
    ) {
        $this->name     = isset($name) ? trim($name) : null;
        $this->email    = isset($email) ? strtolower(trim($email)) : null;

        // Normalize gender to lowercase
        $this->gender   = isset($gender) ? strtolower(trim($gender)) : null;

        $this->dob      = isset($dob) ? trim($dob) : null;
        $this->address  = isset($address) ? trim($address) : null;
        $this->phone    = isset($phone) ? trim($phone) : null;

        // Password will be hashed in service if needed
        $this->password = $password;
    }

    public static function fromRequest(UpdatePatientRequest $request): self
    {
        $data = $request->validated();

        return new self(
            $data['name']     ?? null,
            $data['email']    ?? null,
            $data['gender']   ?? null,
            $data['dob']      ?? null,
            $data['address']  ?? null,
            $data['phone']    ?? null,
            $data['password'] ?? null
        );
    }
}
