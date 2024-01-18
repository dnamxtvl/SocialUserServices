<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string $user_id
 * @property mixed|string $ip
 * @property mixed|string $device
 * @property int|mixed $type
 * @property mixed|string|null $longitude
 * @property mixed|string|null $latitude
 * @property mixed|string|null $country_name
 * @property mixed|string|null $city_name
 */
class UserForgotPasswordLog extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'user_forgot_password_logs';
}
