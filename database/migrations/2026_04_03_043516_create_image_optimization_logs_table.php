<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('image_optimization_logs', function (Blueprint $table) {
            $table->id();

            // ===== Request Info
            $table->string('request_id')->nullable()->index();
            $table->string('route_path')->nullable()->index();
            $table->string('full_url', 2048)->nullable();
            $table->string('http_method', 20)->nullable();

            // ===== Image Info
            $table->unsignedInteger('image_position')->default(0);
            $table->string('image_src', 2048)->nullable();
            $table->string('image_alt')->nullable();
            $table->string('image_class')->nullable();
            $table->string('image_id')->nullable();
            $table->string('image_role')->nullable()->index();

            // ===== Optimization Data
            $table->string('status', 50)
                ->nullable()
                ->index()
                ->comment('optimized, skipped, manual-control');

            $table->string('mode', 50)
                ->nullable()
                ->index()
                ->comment('critical, important, deferred');

            $table->integer('score')
                ->default(0)
                ->index()
                ->comment('Optimization score (0-100)');

            $table->enum('confidence', ['low', 'medium', 'high'])
                ->default('low')
                ->comment('Optimization confidence (low, medium, high)');

            $table->string('loading_value', 20)->nullable();
            $table->string('fetchpriority_value', 20)->nullable();
            $table->string('decoding_value', 20)->nullable();

            $table->unsignedInteger('image_width')->nullable();
            $table->unsignedInteger('image_height')->nullable();

            // ===== JSON Data
            $table->json('reasons')->nullable();
            $table->json('context_payload')->nullable();

            // ===== Request Meta
            $table->string('user_agent', 1000)->nullable();
            $table->string('ip_address', 45)->nullable();

            // ===== Audit Columns
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('deleted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // ===== Timestamps
            $table->timestamps();
            $table->softDeletes();

            // ===== Composite Indexes (Performance 🚀)
            $table->index(['route_path', 'mode']);
            $table->index(['route_path', 'image_position']);
            $table->index('created_at', 'idx_created_at');
        });

        /*
        |--------------------------------------------------------------------------
        | Raw Indexes (MySQL Prefix Index for Large VARCHAR)
        |--------------------------------------------------------------------------
        */
        DB::statement('CREATE INDEX idx_image_src ON image_optimization_logs (image_src(255))');
        DB::statement('CREATE INDEX idx_src_score ON image_optimization_logs (image_src(100), score)');
    }

    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Drop Raw Indexes First (Important ⚠️)
        |--------------------------------------------------------------------------
        */
        DB::statement('DROP INDEX idx_image_src ON image_optimization_logs');
        DB::statement('DROP INDEX idx_src_score ON image_optimization_logs');

        Schema::table('image_optimization_logs', function (Blueprint $table) {
            $table->dropIndex('idx_created_at');
        });

        Schema::dropIfExists('image_optimization_logs');
    }
};