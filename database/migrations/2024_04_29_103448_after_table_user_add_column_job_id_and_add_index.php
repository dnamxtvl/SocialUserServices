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
        Schema::connection('mysql_user')->table('users', function(Blueprint $table)
        {
            $table->integer('from_ward_id')->after('background_profile');
            $table->integer('from_district_id')->after('from_ward_id');
            $table->integer('current_ward_id')->after('from_city_id');
            $table->integer('current_district_id')->after('current_ward_id');
            $table->tinyInteger('type_account')->after('current_city_id');
            $table->integer('job_id')->nullable()->index()->after('type_account');
            $table->integer('organization_id')->index()->after('job_id');
            $table->integer('unit_room_id')->index()->after('organization_id');
            $table->integer('position_id')->nullable()->index()->after('unit_room_id');
            $table->index(['from_city_id', 'from_district_id', 'from_ward_id']);
            $table->index(['from_city_id', 'organization_id','from_district_id']);
            $table->index(['from_city_id', 'from_district_id']);
            $table->index(['organization_id', 'position_id']);
            $table->index(['organization_id', 'job_id']);
            $table->index(['organization_id', 'type_account']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function(Blueprint $table)
        {
            $table->dropColumn(
                [
                    'from_ward_id',
                    'from_district_id',
                    'current_ward_id',
                    'current_district_id',
                    'type_account',
                    'parent_id',
                    'job_id',
                    'organization_id',
                    'unit_room_id',
                    'position_id',
                ]
            );
        });
    }
};
