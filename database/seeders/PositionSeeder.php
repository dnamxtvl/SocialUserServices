<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::query()->truncate();
        $positions = [
            [
                'name' => 'Hiệu Trưởng',
                'type_organization' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hiệu Phó',
                'type_organization' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kế Toán',
                'type_organization' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Văn Thư',
                'type_organization' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Giáo Viên',
                'type_organization' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Phụ Trách Đoàn',
                'type_organization' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Quản Lý Thư Viện',
                'type_organization' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Quản Lý Thiết Bị',
                'type_organization' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bảo Vệ',
                'type_organization' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Phụ Trách Vệ Sinh',
                'type_organization' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($positions as $position) {
            Position::query()->create($position);
        }
    }
}
