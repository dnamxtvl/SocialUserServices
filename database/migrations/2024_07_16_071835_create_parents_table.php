<?php

use App\Domains\User\Enums\UserRelationshipStatusEnum;
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
        Schema::connection('mysql_user')->create('parents', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone')->index();
            $table->integer('job_id')->index();
            $table->string('state_of_work');
            $table->tinyInteger('count_child');
            $table->tinyInteger('relationship_status')->default(UserRelationshipStatusEnum::MARRIED);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
