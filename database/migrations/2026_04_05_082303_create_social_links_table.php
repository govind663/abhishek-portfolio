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
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();

            $table->string('platform')->nullable()->comment('Facebook, Twitter, LinkedIn, GitHub, etc.');
            $table->string('icon')->nullable()->comment('FontAwesome class or custom icon path');
            $table->string('url')->nullable()->comment('Full URL to the social profile');

            // ===== STATUS
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Only active links will be displayed on the frontend');

            // ===== AUDIT COLUMNS
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            // ===== TIMESTAMPS
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};
