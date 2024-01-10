<?php

namespace App\Domains\Auth\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OTPNotFoundException extends NotFoundHttpException
{
    public function __construct(string $message = 'Mã OTP không tồn tại hoặc đã xảy ra lỗi trong quá trình xác thực!', int $code = 0)
    {
        parent::__construct(message: $message, code: $code);
    }
}
