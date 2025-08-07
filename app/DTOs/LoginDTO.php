<?php

namespace App\DTOs;

class LoginDTO
{
    /**
     * The user's email address.
     */
    public string $email;

    /**
     * The user's password.
     */
    public string $password;

    /**
     * Create a new LoginDTO instance.
     */
    public function __construct(array $data)
    {
        // You could validate structure here if needed
        $this->email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $this->password = (string) ($data['password'] ?? '');
    }

    /**
     * Convert DTO to an array (optional helper).
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
