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
        Schema::create('email_verify_otps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', config('validation.length_of_verify_code'))->fulltext();
            $table->uuid('user_id')->fulltext();
            $table->timestamp('expired_at')->nullable();
            $table->index(['code', 'user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_verify_otps');
    }
};
