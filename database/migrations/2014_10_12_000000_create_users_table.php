<?php

use App\Domains\User\Enums\UserStatusActiveUnum;
use App\Domains\User\Enums\UserStatusEnum;
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
            $table->string('user_code')->unique();
            $table->string('first_name', config('validation.first_name.max_length'));
            $table->string('last_name', config('validation.last_name.max_length'));
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_number', config('user.phone_number_length'))->index()->nullable();
            $table->char('identity_id', config('user.identity_id_length'))->unique()->nullable();
            $table->char('place_identity')->nullable();
            $table->timestamp('license_date')->nullable();
            $table->string('address')->nullable();
            $table->string('avatar')->nullable();
            $table->string('background_profile')->nullable();
            $table->integer('from_city_id')->nullable()->comment('Quê quán')->index();
            $table->integer('from_district_id');
            $table->integer('from_ward_id');
            $table->integer('current_city_id')->nullable()->comment('Nơi ở hiện tại');
            $table->integer('current_district_id');
            $table->integer('current_ward_id');
            $table->tinyInteger('type_account');
            $table->tinyInteger('gender')->comment('0:nữ, 1:nam, 2:khác');
            $table->integer('day_of_birth')->nullable();
            $table->integer('month_of_birth')->nullable();
            $table->integer('year_of_birth')->nullable()->index();
            $table->string('about_me')->nullable();
            $table->string('password');
            $table->timestamp('latest_login')->nullable();
            $table->string('latest_ip_login')->nullable();
            $table->timestamp('last_activity_at')->nullable()->comment('Thời gian online cuối');
            $table->tinyInteger('status')->default(UserStatusEnum::ACTIVE);
            $table->tinyInteger('status_active')->default(UserStatusActiveUnum::OFFLINE)->comment('Trạng thái hooạt động');
            $table->uuid('userable_id')->index();
            $table->string('userable_type');
            $table->index(['first_name', 'last_name']);
            $table->index(['year_of_birth', 'month_of_birth']);
            $table->index(['year_of_birth', 'month_of_birth', 'day_of_birth']);
            $table->index(['from_city_id', 'from_district_id', 'from_ward_id']);
            $table->index(['from_city_id', 'from_district_id']);
            $table->index(['userable_id', 'userable_type']);
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
