<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mysql_user')->create('schools', function (Blueprint $table) {
            $table->id()->startingValue(10000);
            $table->string('name')->index();
            $table->string('slug')->index();
            $table->char('tax_code', config('organization.tax_code_length'))->unique();
            $table->string('address');
            $table->string('link_website')->nullable();
            $table->integer('count_person_from')->nullable();
            $table->integer('count_person_to');
            $table->integer('district_id');
            $table->integer('city_id')->index();
            $table->integer('ward_id');
            $table->char('hotline_phone', config('organization.hr_phone_length'))->index();
            $table->timestamp('founding_date')->nullable();
            $table->tinyInteger('type')->index();
            $table->string('background_image')->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->index(['city_id', 'district_id']);
            $table->index(['city_id', 'district_id', 'ward_id']);
            $table->index(['city_id', 'type', 'is_active']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
