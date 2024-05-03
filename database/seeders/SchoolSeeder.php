<?php

namespace Database\Seeders;

use App\Infrastructure\Models\City;
use App\Infrastructure\Models\District;
use App\Infrastructure\Models\School;
use App\Infrastructure\Models\Ward;
use Illuminate\Database\Seeder;
use Throwable;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bar = $this->command->getOutput()->createProgressBar(100);
        $bar->start();

        School::query()->truncate();
        $districts = District::query()->pluck('id')->toArray();
        $cities = City::query()->pluck('id')->toArray();
        $wards = Ward::query()->pluck('id')->toArray();
        $schools = [];
        $now = now();
        try {
            for ($i = 0; $i < 100; $i++) {
                for ($j = 0; $j < 200; $j++) {
                    $schools[] = [
                        'name' => 'Trường Trung Học Cở Sở ' . fake()->name,
                        'slug' => fake()->slug,
                        'tax_code' => $this->getRandomTaxCode(),
                        'address' => fake()->address,
                        'link_website' => fake()->url,
                        'count_person_from' => fake()->numberBetween(0, 100),
                        'count_person_to' => fake()->numberBetween(0, 100),
                        'district_id' => array_rand($districts),
                        'city_id' => array_rand($cities),
                        'ward_id' => array_rand($wards),
                        'hotline_phone' => (string) fake()->numberBetween(1000000000, 9999999999),
                        'founding_date' => fake()->dateTime(),
                        'type' => fake()->numberBetween(1, 3),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                School::query()->insert($schools);
                $schools = [];
                $bar->advance();
            }
            $bar->finish();
        } catch (Throwable $th) {
            $bar->finish();
            $this->command->error($th);
        }
    }

    private function getRandomTaxCode(): string
    {
        $taxCode = (string) rand(1000000000, 9999999999);

        if (School::query()->where('tax_code', $taxCode)->exists()) {
            return $this->getRandomTaxCode();
        }

        return $taxCode;
    }
}
