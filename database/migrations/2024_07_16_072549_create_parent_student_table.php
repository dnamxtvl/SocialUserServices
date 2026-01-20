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
        Schema::connection('mysql_user')->create('parent_student', function (Blueprint $table) {
            $table->id();
            $table->uuid('parent_id')->index();
            $table->uuid('student_id')->index();
            $table->tinyInteger('relationship')->default(0);
            $table->index(['parent_id', 'student_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_student');
    }
};
