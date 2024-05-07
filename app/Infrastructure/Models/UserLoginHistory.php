<?php

namespace App\Infrastructure\Models;

use App\Domains\Auth\Enums\TypeUserHistoryLoginEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string $ip
 * @property int|mixed $user_id
 * @property mixed|string $device
 * @property TypeUserHistoryLoginEnum|mixed $type
 * @property mixed|string|null $longitude
 * @property mixed|string|null $latitude
 * @property mixed|string|null $country_name
 * @property mixed|string|null $city_name
 */
class UserLoginHistory extends Model
{
    use HasFactory;

    protected $connection = 'mysql_user';

    protected $table = 'user_history_login';

    protected $primaryKey = 'id';
}
