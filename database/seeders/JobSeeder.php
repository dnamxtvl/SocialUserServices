<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Job;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Job::query()->truncate();
        $jobs = [
            [
                'name' => 'Làm ruộng',
                'type_organization' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Văn Phòng',
                'type_organization' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cán bộ',
                'type_organization' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Công an',
                'type_organization' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Quân đội',
                'type_organization' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bác sỹ',
                'type_organization' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Giáo viên',
                'type_organization' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($jobs as $job) {
            Job::query()->create($job);
        }
    }
}
