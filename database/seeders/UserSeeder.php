<?php

namespace Database\Seeders;

use App\Infrastructure\Models\City;
use App\Infrastructure\Models\District;
use App\Infrastructure\Models\Job;
use App\Infrastructure\Models\Position;
use App\Infrastructure\Models\User;
use App\Infrastructure\Models\Ward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->truncate();
        $bar = $this->command->getOutput()->createProgressBar(1000);
        $bar->start();
        $users = [];
        $password = Hash::make('password');
        $districts = District::query()->pluck('id')->toArray();
        $cities = City::query()->pluck('id')->toArray();
        $wards = Ward::query()->pluck('id')->toArray();
        $jobs = Job::query()->pluck('id')->toArray();
        $positions = Position::query()->pluck('id')->toArray();
        $firstUserCode = 10000000;
        try {
            for ($i = 0; $i < 1000; $i++) {
                for ($j = 0; $j < 500; $j++) {
                    $users[] = [
                        'id' => $this->getRandomId(),
                        'first_name' => fake()->firstName(),
                        'last_name' => fake()->lastName(),
                        'email' => $this->getRandomEmail(),
                        'email_verified_at' => now(),
                        'phone_number' => fake('vi_VN')->phoneNumber(),
                        'gender' => fake()->numberBetween(config('user.gender.female'), config('user.gender.other')),
                        'password' => $password,
                        'relationship_status' => fake()->numberBetween(config('user.relationship_status.single'), config('user.relationship_status.live_together')),
                        'latest_login' => fake()->dateTimeBetween(now()->subYear(), now()),
                        'latest_ip_login' => fake()->ipv4(),
                        'day_of_birth' => fake()->dayOfMonth(),
                        'last_activity_at' => fake()->dateTimeBetween(now()->subYear(), now()),
                        'status_active' => fake()->numberBetween(config('user.status_active.online'), config('user.status_active.offline')),
                        'month_of_birth' => fake()->numberBetween(config('validation.month.min_value'), config('validation.month.max_value')),
                        'year_of_birth' => fake()->numberBetween(config('validation.year.min_value'), now()->subYear()->year),
                        'remember_token' => Str::random(10),
                        'identity_id' => $this->getRandomIdentityId(),
                        'user_code' => $firstUserCode,
                        'address' => fake()->address,
                        'from_ward_id' => array_rand($wards),
                        'from_district_id' => array_rand($districts),
                        'from_city_id' => array_rand($cities),
                        'current_ward_id' => array_rand($wards),
                        'current_district_id' => array_rand($districts),
                        'current_city_id' => array_rand($cities),
                        'job_id' => array_rand($jobs),
                        'organization_id' => rand(1, 20000),
                        'unit_room_id' => rand(1, 100000),
                        'position_id' => array_rand($positions),
                        'type_account' => rand(1, 3),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $firstUserCode ++;
                }

                User::query()->insert($users);
                $users = [];
                $bar->advance();
            }
            $bar->finish();
        } catch (Throwable $th) {
            $bar->finish();
            $this->command->error($th);
        }
    }

    private function getRandomIdentityId(): string
    {
        $identityId = (string) rand(100000000000, 999999999999);

        if (User::query()->select('id', 'identity_id')->where('identity_id', $identityId)->exists()) {
            return $this->getRandomIdentityId();
        }

        return $identityId;
    }

    private function getRandomId(): string
    {
        $id = Str::uuid();

        if (User::query()->find($id)) {
            return $this->getRandomId();
        }

        return $id;
    }

    private function getRandomEmail(): string
    {
        $email = now()->timestamp . Str::random(20) . fake()->email;

        if (User::query()->where('email', $email)->exists()) {
            return $this->getRandomEmail();
        }

        return $email;
    }
}
