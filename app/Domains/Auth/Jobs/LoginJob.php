<?php

namespace App\Domains\Auth\Jobs;

use App\Domains\Auth\DTOs\UserLoginResponseDataDTO;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Laravel\Horizon\Exceptions\ForbiddenException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpFoundation\Response;

class LoginJob
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private readonly string $email,
        private readonly string $password,
        private readonly bool $rememberMe = false,
    ) {}

    /**
     * Execute the job.
     *
     * @throws Exception
     */
    public function handle(): UserLoginResponseDataDTO
    {
        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (! Auth::attempt($credentials, $this->rememberMe)) {
           throw new UnauthorizedHttpException(challenge: 'Invalid Argument', message: 'Sai email hoặc mật khẩu!');
        }

        $user = Auth::user();

        if (! $user->hasVerifiedEmail()) {
            event(new Registered($user));
            throw new ForbiddenException(statusCode: Response::HTTP_FORBIDDEN, message: 'Tài khoản chưa được xác thực, chúng tôi đã gửi email xác thực đến tài khoản của bạn!');
        }

        $tokenResult = $user->createToken('API Token');
        $token = $tokenResult->token;
        if ($this->rememberMe) {
            $token->expires_at = now()->addWeek();
            $token->save();
        }

        return new UserLoginResponseDataDTO(
            user: $user,
            token: $tokenResult->accessToken,
            expiresAt: Carbon::parse($tokenResult->token->expires_at)
        );
    }
}
