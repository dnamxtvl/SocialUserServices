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
        Schema::connection('mysql_user')->create('class_rooms', function (Blueprint $table) {
            $table->id()->startingValue(1000000);
            $table->string('class_code')->unique();
            $table->string('name');
            $table->integer('level_school');
            $table->tinyInteger('majors')->nullable();
            $table->integer('school_id')->index();
            $table->string('school_course_code');
            $table->uuid('teacher_id')->index()->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_rooms');
    }
};
