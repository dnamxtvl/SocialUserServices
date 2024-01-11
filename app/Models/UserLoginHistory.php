<?php

namespace App\Models;

use App\Domains\Auth\Enums\TypeUserHistoryLoginEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string $ip
 * @property int|mixed $user_id
 * @property mixed|string $device
 * @property TypeUserHistoryLoginEnum|mixed $type
 */
class UserLoginHistory extends Model
{
    use HasFactory;

    protected $table = 'user_history_login';

    protected $primaryKey = 'id';
}
