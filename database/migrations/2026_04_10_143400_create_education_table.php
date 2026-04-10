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
        Schema::create('educations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('resume_id')->constrained()->cascadeOnDelete()->index();

            $table->string('degree');
            $table->string('field')->nullable();
            $table->string('institution');
            $table->string('university')->nullable();
            $table->string('location')->nullable();

            // ✅ consistent date
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');

            // 🔥 Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educations');
    }
};
