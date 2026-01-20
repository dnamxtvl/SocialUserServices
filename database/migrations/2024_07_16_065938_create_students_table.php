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
        Schema::connection('mysql_user')->create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->tinyInteger('current_grade_level');
            $table->integer('latest_class_id')->index();
            $table->timestamp('latest_class_join_at');
            $table->integer('latest_school_id')->index();
            $table->timestamp('latest_school_join_at');
            $table->string('emergency_contact_phone')->index();
            $table->string('emergency_contact_name');
            $table->tinyInteger('emergency_contact_relationship');
            $table->tinyInteger('latest_semester');
            $table->timestamp('latest_semester_join_at');
            $table->float('latest_semester_score')->nullable();
            $table->integer('position_class_id')->default(0);
            $table->tinyInteger('type_residence')->default(0);
            $table->tinyInteger('status_stu')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
