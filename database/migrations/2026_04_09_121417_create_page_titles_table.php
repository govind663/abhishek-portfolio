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
        Schema::create('page_titles', function (Blueprint $table) {
            $table->id();

            // 🔥 Data Columns
            $table->string('page_name')->comment('Unique identifier for the page, e.g., home, about, contact');
            $table->string('title')->nullable()->comment('SEO-friendly title for the page');
            $table->text('description')->nullable()->comment('SEO-friendly description for the page');

            // 🔥 Status Column
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Indicates whether the page title is active or inactive');

            // 🔥 Audit Columns
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_titles');
    }
};
