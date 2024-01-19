<?php

namespace App\Domains\Auth\Jobs;

use App\Domains\Auth\DTOs\UserLoginResponseDataDTO;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\Token;

class CreateTokenJwtLoginJob
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private readonly Model $user,
        private readonly bool $rememberMe = false
    ) {
    }

    /**
     * Execute the job.
     *
     * @throws Exception
     */
    public function handle(): UserLoginResponseDataDTO
    {
        $user = $this->user;
        /** @var User $user */
        $tokenResult = $user->createToken('API Token');
        $token = $tokenResult->token;
        /** @var Token $token */
        if ($this->rememberMe) {
            $token->setAttribute('expires_at', Carbon::now()->addWeek());
            $token->save();
        }

        return new UserLoginResponseDataDTO(
            user: $user,
            token: $tokenResult->accessToken,
            expiresAt: Carbon::parse($token->getAttribute(/** @lang text */ 'expires_at'))
        );
    }
}
