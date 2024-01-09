<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string $code
 * @property mixed|string $user_id
 * @property Carbon|mixed $expired_at
 */
class EmailVerifyOTO extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'email_verify_otps';
}
