<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ Drop if exists (fresh structure)
        Schema::dropIfExists('image_meta');

        Schema::create('image_meta', function (Blueprint $table) {

            $table->id();

            // ===== MAIN
            $table->string('path')->unique();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();

            // ===== EXTRA
            $table->string('type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            // ✅ MERGED FIELD (from second migration)
            $table->string('hash')->nullable()->index();

            // ===== AUDIT
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

            // ===== TIMESTAMPS
            $table->timestamps();
            $table->softDeletes();

            // ===== INDEXES
            $table->index('path');
            $table->index(['width', 'height']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('image_meta');
    }
};