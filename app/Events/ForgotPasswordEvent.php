<?php

namespace App\Events;

use App\Domains\Auth\Enums\TypeCodeOTPEnum;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly Model $user,
        public readonly string $verifyCode,
        public readonly TypeCodeOTPEnum $type
    ) {
    }
}
