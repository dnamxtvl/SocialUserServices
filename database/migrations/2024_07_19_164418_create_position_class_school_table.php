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
        Schema::connection('mysql_user')->create('position_class_school', function (Blueprint $table) {
            $table->id();
            $table->uuid('school_id')->index();
            $table->integer('class_id')->index();
            $table->integer('position_class_id');
            $table->boolean('is_default')->default(true);
            $table->char('default_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_class_school');
    }
};
