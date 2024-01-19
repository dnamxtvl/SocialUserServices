<?php

namespace App\Models;

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
 */
class User extends Authenticate implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasUuids, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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
        return $this->hasMany(EmailVerifyOTO::class);
    }

    public function blockUserLoginTemporary(): HasMany
    {
        return $this->hasMany(BlockUserLoginTemporary::class);
    }
}
