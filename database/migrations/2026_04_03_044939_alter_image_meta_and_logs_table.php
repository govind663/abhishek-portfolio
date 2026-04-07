<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ image_meta update
        Schema::table('image_meta', function (Blueprint $table) {

            if (!Schema::hasColumn('image_meta', 'type')) {
                $table->string('type')->nullable()->after('height');
            }

            if (!Schema::hasColumn('image_meta', 'file_size')) {
                $table->unsignedBigInteger('file_size')->nullable()->after('type');
            }
        });

        // ✅ logs table relation
        Schema::table('image_optimization_logs', function (Blueprint $table) {

            if (!Schema::hasColumn('image_optimization_logs', 'image_meta_id')) {

                $table->foreignId('image_meta_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('image_meta')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('image_optimization_logs', function (Blueprint $table) {
            if (Schema::hasColumn('image_optimization_logs', 'image_meta_id')) {
                $table->dropForeign(['image_meta_id']);
                $table->dropColumn('image_meta_id');
            }
        });

        Schema::table('image_meta', function (Blueprint $table) {
            if (Schema::hasColumn('image_meta', 'type')) {
                $table->dropColumn('type');
            }

            if (Schema::hasColumn('image_meta', 'file_size')) {
                $table->dropColumn('file_size');
            }
        });
    }
};