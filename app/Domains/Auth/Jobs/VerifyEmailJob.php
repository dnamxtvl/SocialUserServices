<?php

namespace App\Domains\Auth\Jobs;

use App\Infrastructure\Models\User;
use Illuminate\Database\Eloquent\Model;

class VerifyEmailJob
{
    public function __construct(
        private readonly Model $user
    ) {
    }

    public function handle(): void
    {
        $user = $this->user;
        /** @var User $user */
        $user->email_verified_at = now();
        $user->save();
        $user->emailVerifyOTPs()->delete();
    }
}
