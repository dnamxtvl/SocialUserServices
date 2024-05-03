<?php

use App\Domains\Auth\Enums\TypeCodeOTPEnum;
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
        Schema::connection('mysql_user')->create('email_verify_otps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', config('validation.length_of_verify_code'))->fulltext();
            $table->uuid('user_id')->fulltext();
            $table->timestamp('expired_at')->nullable();
            $table->integer('type')->default(TypeCodeOTPEnum::VERIFY_EMAIL->value)->index()
                ->comment('Loại OTP gồm 1:xác thực email,2:OTP quên mật khẩu,3: OTP login ip lạ');
            $table->string('token');
            $table->index(['user_id', 'type']);
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
