<?php

namespace App\Infrastructure\Models;

use App\Domains\Auth\Notification\VerifyEmailRegister;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticate;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Passport\HasApiTokens;

/**
 * @property mixed|string $last_name
 * @property mixed|string $first_name
 * @property mixed|string $email
 * @property mixed|string $password
 * @property int|mixed $day_of_birth
 * @property int|mixed $month_of_birth
 * @property int|mixed $year_of_birth
 * @property int|mixed $gender
 * @property int|mixed $status
 * @property mixed $id
 * @property Carbon|mixed $email_verified_at
 * @property mixed $latest_ip_login
 * @property mixed $userLoginHistories
 * @property Carbon|mixed $latest_login
 * @property int|mixed $user_code
 * @property int|mixed $from_city_id
 * @property int|mixed $from_district_id
 * @property int|mixed $from_ward_id
 * @property int|mixed $current_city_id
 * @property int|mixed $current_district_id
 * @property int|mixed $current_ward_id
 * @property int|mixed $type_account
 * @property int|mixed $organization_id
 * @property int|mixed $unit_room_id
 * @property mixed|string $identity_id
 * @property int|mixed $status_active
 * @property mixed $created_at
 * @property mixed $latest_active_at
 * @property mixed $position_id
 * @property mixed $job_id
 */
class User extends Authenticate implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasUuids, Notifiable, SoftDeletes;

    protected $connection = 'mysql_user';

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'latest_login' => 'datetime',
        'latest_active_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function userLoginHistories(): HasMany
    {
        return $this->hasMany(UserLoginHistory::class);
    }

    public function sendEmailVerifyNotification(string $verifyCode): void
    {
        $this->notify(new VerifyEmailRegister(verifyCode: $verifyCode));
    }

    public function emailVerifyOTPs(): HasMany
    {
        return $this->hasMany(EmailVerifyOTP::class);
    }

    public function blockUserLoginTemporary(): HasMany
    {
        return $this->hasMany(BlockUserLoginTemporary::class);
    }
}
