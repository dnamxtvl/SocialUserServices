<?php

namespace App\Application\UseCases;

use App\Domains\Auth\Enums\AuthExceptionEnum;
use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use App\Domains\Auth\Exceptions\EmailVerifiedException;
use App\Domains\Auth\Jobs\CheckIsValidVerifyEmailOTPJob;
use App\Domains\Auth\Jobs\CreateTokenJwtLoginJob;
use App\Domains\Auth\Jobs\VerifyEmailJob;
use App\Domains\User\Enums\UserExceptionEnum;
use App\Domains\User\Exceptions\UserNotFoundException;
use App\Domains\User\Repository\UserRepositoryInterface;
use App\Application\Command;
use App\Infrastructure\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class VerifyOTPAfterLoginUseCase extends Command
{
    public function __construct(
        private readonly string $email,
        private readonly string $code
    ) {
    }

    public function handle(UserRepositoryInterface $userRepository): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = $userRepository->getQuery(filters: ['email' => $this->email])->first();
            if (is_null($user)) {
                throw new UserNotFoundException(code: UserExceptionEnum::USER_NOT_FOUND_WHEN_VERIFY_OTP->value);
            }

            if ($user->hasVerifiedEmail()) {
                throw new EmailVerifiedException(code: AuthExceptionEnum::EMAIL_VERIFIED->value);
            }

            $this->dispatchSync(new CheckIsValidVerifyEmailOTPJob(
                user: $user,
                verifyCode: $this->code,
                typeOTP: TypeCodeOTPEnum::VERIFY_EMAIL
            ));

            $this->dispatchSync(new VerifyEmailJob(user: $user));

            /** @var User $user */
            Auth::login($user);
            $userLogin = $this->dispatchSync(new CreateTokenJwtLoginJob(user: $user));

            DB::commit();

            return $this->respondWithJson(content: $userLogin->toArray());
        } catch (Throwable $exception) {
            DB::rollBack();

            return $this->respondWithJsonError(e: $exception);
        }
    }
}
