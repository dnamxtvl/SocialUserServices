<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed|string $code
 * @property mixed|string $user_id
 * @property Carbon|mixed $expired_at
 * @property mixed|string $type
 * @property mixed $user
 * @property mixed|string $token
 */
class EmailVerifyOTO extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'email_verify_otps';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
