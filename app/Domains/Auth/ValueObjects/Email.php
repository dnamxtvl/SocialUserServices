<?php

namespace App\Domains\Auth\ValueObjects;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use InvalidArgumentException;

readonly class Email
{
    public function __construct(
        private string $email
    ) {
        $this->validateEmail(email: $this->email);
    }

    private function validateEmail(string $email): void
    {
        $validator = Validator::make(data: ['email' => $email], rules: [
            'email' => 'required|email',
        ]);

        if ($validator->fails() || Str::length($email) > config('validation.email.max_length')) {
            throw new InvalidArgumentException(message: 'Email không hợp lệ.');
        }
    }

    public function getStringEmail(): string
    {
        return $this->email;
    }
}
