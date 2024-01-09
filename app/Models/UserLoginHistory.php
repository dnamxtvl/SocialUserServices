<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string $ip
 * @property int|mixed $user_id
 * @property mixed|string $device
 */
class UserLoginHistory extends Model
{
    use HasFactory;

    protected $table = 'user_history_login';

    protected $primaryKey = 'id';
}
