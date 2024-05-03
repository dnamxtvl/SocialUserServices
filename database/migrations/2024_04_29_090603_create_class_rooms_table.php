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
            $table->string('name')->index();
            $table->integer('school_id')->index();
            $table->uuid('teacher_id')->index();
            $table->integer('count_student');
            $table->timestamps();
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
