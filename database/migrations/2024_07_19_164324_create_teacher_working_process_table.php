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
        Schema::connection('mysql_user')->create('teacher_working_process', function (Blueprint $table) {
            $table->id();
            $table->uuid('school_account_id')->index();
            $table->integer('class_id')->nullable();
            $table->integer('school_id');
            $table->tinyInteger('semester');
            $table->tinyInteger('contract_type');
            $table->string('salary')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->json('teacher_score_process')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_working_process');
    }
};
