<?php

namespace App\Domains\Auth\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserNotFoundException extends NotFoundHttpException
{
    public function __construct(string $message = 'Không tồn tại user!', int $code = 0)
    {
        parent::__construct(message: $message, code: $code);
    }
}
