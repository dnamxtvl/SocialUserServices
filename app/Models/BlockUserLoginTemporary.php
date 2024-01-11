<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Carbon|mixed $expired_at
 * @property mixed|string $user_id
 * @property mixed|string $ip
 */
class BlockUserLoginTemporary extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'block_user_login_temporaries';
}
