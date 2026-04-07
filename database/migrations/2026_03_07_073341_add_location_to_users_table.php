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
        Schema::table('users', function (Blueprint $table) {

            $table->string('ip_address')->nullable()->after('remember_token');
            $table->string('country')->nullable()->after('ip_address');
            $table->string('city')->nullable()->after('country');
            $table->decimal('latitude', 10, 7)->nullable()->after('city');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->text('user_agent')->nullable()->after('longitude');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'ip_address',
                'country',
                'city',
                'latitude',
                'longitude',
                'user_agent'
            ]);

        });
    }
};