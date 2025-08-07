<?php

namespace App\DTOs;

use App\Http\Requests\PatientRequest;

class PatientDTO
{
    public string $name;
    public string $email;
    public string $password;
    public string $gender;
    public string $dob;
    public string $address;
    public string $phone;

    public function __construct(
        string $name,
        string $email,
        string $password,
        string $gender,
        string $dob,
        string $address,
        string $phone
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->gender = $gender;
        $this->dob = $dob;
        $this->address = $address;
        $this->phone = $phone;
    }

    public static function fromRequest(PatientRequest $request): self
    {
        return new self(
            trim($request->input('name')),
            strtolower(trim($request->input('email'))),
            $request->input('password'), // You may hash it here if needed
            $request->input('gender'),
            $request->input('dob'),
            trim($request->input('address')),
            trim($request->input('phone'))
        );
    }
}
