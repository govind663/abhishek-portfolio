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
        Schema::create('resume_drafts', function (Blueprint $table) {

            $table->id();

            // 🔥 Core relation
            $table->unsignedBigInteger('resume_id');
            $table->unique('resume_id');

            $table->json('data')->nullable();

            // 🔥 Audit columns
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            // 🔥 Foreign keys
            $table->foreign('resume_id', 'fk_resume_drafts_resume_id')
                ->references('id')->on('resumes')
                ->onDelete('cascade');

            $table->foreign('created_by', 'fk_resume_drafts_created_by')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->foreign('updated_by', 'fk_resume_drafts_updated_by')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->foreign('deleted_by', 'fk_resume_drafts_deleted_by')
                ->references('id')->on('users')
                ->nullOnDelete();

            // 🔥 System columns
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume_drafts');
    }
};