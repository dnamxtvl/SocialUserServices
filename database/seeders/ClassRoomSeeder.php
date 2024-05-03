<?php

namespace Database\Seeders;

use App\Data\Models\ClassRoom;
use App\Data\Models\School;
use App\Data\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Throwable;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bar = $this->command->getOutput()->createProgressBar(5000);
        $bar->start();

        /** @var User $user */
        $users = User::query()
            ->offset(rand(0, 700000))
            ->limit(100000)->pluck('id')->toArray();
        ClassRoom::query()->truncate();
        $schools = School::query()->pluck('id')->toArray();
        $classrooms = [];
        $now = now();
        try {
            for ($i = 0; $i < 5000; $i++) {
                for ($j = 0; $j < 200; $j++) {
                    $classrooms[] = [
                        'name' => rand(1, 12) . Str::random(2),
                        'school_id' => array_rand($schools),
                        'teacher_id' => count($users) > 0 ? array_rand($users) : Str::uuid(),
                        'count_student' => rand(1, 100),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                ClassRoom::query()->insert($classrooms);
                $classrooms = [];
                $bar->advance();
            }
            $bar->finish();
        } catch (Throwable $th) {
            $bar->finish();
            $this->command->error($th);
        }
    }
}
