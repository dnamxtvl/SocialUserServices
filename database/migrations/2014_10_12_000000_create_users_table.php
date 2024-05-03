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
        Schema::connection('mysql_user')->create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('identity_id', config('user.identity_id_length'))->unique()->nullable();
            $table->integer('user_code')->unique();
            $table->string('first_name', config('validation.first_name.max_length'))->fulltext();
            $table->string('last_name', config('validation.last_name.max_length'))->fulltext();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_number', 20)->nullable()->fulltext();
            $table->string('avatar')->nullable();
            $table->string('background_profile')->nullable();
            $table->integer('from_city_id')->nullable()->comment('Quê quán')->index();
            $table->integer('current_city_id')->nullable()->comment('Nơi ở hiện tại');
            $table->integer('relationship_status')->nullable()->comment('Tình trạng mối quan hệ');
            $table->tinyInteger('gender')->comment('0:nữ, 1:nam, 2:khác');
            $table->integer('day_of_birth')->nullable();
            $table->integer('month_of_birth')->nullable();
            $table->integer('year_of_birth')->nullable()->index();
            $table->string('about_me')->nullable();
            $table->string('password');
            $table->timestamp('latest_login')->nullable();
            $table->string('latest_ip_login')->nullable();
            $table->timestamp('last_activity_at')->nullable()->comment('Thời gian online cuối');
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('status_active')->default(0)->comment('Trạng thái hooạt động');
            $table->index(['first_name', 'last_name']);
            $table->index(['day_of_birth', 'month_of_birth', 'year_of_birth']);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
