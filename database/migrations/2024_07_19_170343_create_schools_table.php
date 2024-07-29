<?php

use Carbon\Carbon;
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
            $table->string('school_code')->unique();
            $table->string('tax_code')->unique();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('hotline_phone')->nullable();
            $table->string('website')->nullable();
            $table->integer('count_student');
            $table->integer('count_teacher');
            $table->integer('count_staff');
            $table->integer('count_class');
            $table->integer('count_classroom');
            $table->integer('ward_id');
            $table->integer('district_id');
            $table->integer('city_id')->index();
            $table->timestamp('founding_date');
            $table->timestamp('summary date')->nullable();
            $table->timestamp('end_date')->default(Carbon::createFromDate(year: now()->year, month: 9, day: 5));
            $table->timestamp('start_semester_1')->default(Carbon::createFromDate(year: now()->year + 1, month: 1, day: 20));
            $table->timestamp('start_semester_2')->default(Carbon::createFromDate(year: now()->year + 1, month: 1, day: 28));
            $table->string('avatar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_block')->default(false);
            $table->tinyInteger('level');
            $table->index(['city_id', 'district_id']);
            $table->index(['city_id', 'level']);
            $table->index(['city_id', 'district_id', 'level']);
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
