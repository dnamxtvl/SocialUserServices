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
        Schema::connection('mysql_user')->create('school_accounts', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->integer('department_id')->index();
            $table->integer('subject_specialization')->nullable();
            $table->integer('years_of_experience');
            $table->tinyInteger('qualification_id');
            $table->timestamp('hire_date');
            $table->string('about')->nullable();
            $table->integer('school_id')->index();
            $table->integer('position_id');
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->index()->nullable();
            $table->tinyInteger('emergency_contact_relationship')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->tinyInteger('current_contract_type')->default(1);
            $table->tinyInteger('status_sch')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_accounts');
    }
};
