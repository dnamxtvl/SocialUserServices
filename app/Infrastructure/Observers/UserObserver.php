<?php

namespace App\Infrastructure\Observers;

use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use App\Events\RegistedUserEvent;
use App\Infrastructure\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $code = rand(config('validation.verify_code.min_value'), config('validation.verify_code.max_value'));
        event(new RegistedUserEvent(user: $user, verifyCode: $code, type: TypeCodeOTPEnum::VERIFY_EMAIL));
    }
}
