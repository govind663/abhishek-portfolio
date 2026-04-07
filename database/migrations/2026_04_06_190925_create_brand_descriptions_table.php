<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand_descriptions', function (Blueprint $table) {
            $table->id();

            // ===== CONTENT
            $table->string('logo')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();

            // ===== STATUS
            $table->enum('status', ['active', 'inactive'])->default('active');

            // ===== AUDIT COLUMNS
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            // ===== TIMESTAMPS
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_descriptions');
    }
};