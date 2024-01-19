<?php

namespace App\Domains\Auth\Jobs;

use App\Domains\Auth\DTOs\SaveEmailVerifyOTPDTO;
use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use App\Domains\Auth\Events\RegistedUserEvent;
use App\Domains\Auth\Repository\EmailVerifyOTPRepositoryInterface;
use App\Data\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Password;

class CreateEmailVerifyOTPJob
{
    public function __construct(
        private readonly ?Model $user,
        private readonly TypeCodeOTPEnum $type
    ) {
    }

    public function handle(EmailVerifyOTPRepositoryInterface $emailVerifyOTPRepository): void
    {
        $code = rand(config('validation.verify_code.min_value'), config('validation.verify_code.max_value'));
        $user = $this->user;
        /**
         * @var User $user
         **/
        $saveEmailVerifyDTO = new SaveEmailVerifyOTPDTO(
            code: (string) $code,
            userId: $user->id,
            expiredAt: now()->addHour(),
            type: $this->type,
            token: Password::createToken($user)
        );
        $emailVerifyOTPRepository->save(saveEmailVerify: $saveEmailVerifyDTO);
        event(new RegistedUserEvent(user: $user, verifyCode: $code));
    }
}
