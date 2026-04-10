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
        // 🔥 FK error avoid
        Schema::disableForeignKeyConstraints();

        // 🔥 अगर table exist करे तो delete
        Schema::dropIfExists('experiences');

        // 🔥 फिर enable
        Schema::enableForeignKeyConstraints();

        // ✅ Fresh create
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();

            // ✅ Resume FK (manual name)
            $table->unsignedBigInteger('resume_id');
            $table->foreign('resume_id', 'fk_experiences_resume')
                ->references('id')
                ->on('resumes')
                ->cascadeOnDelete();

            $table->string('designation');
            $table->string('company');
            $table->string('location')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // 🔥 Current job support
            $table->boolean('is_current')->default(false);

            $table->enum('status', ['active', 'inactive'])->default('active');

            // ✅ Audit Columns
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            // 🔥 Audit FK (manual names)
            $table->foreign('created_by', 'fk_experiences_created_by')
                ->references('id')->on('users')->nullOnDelete();

            $table->foreign('updated_by', 'fk_experiences_updated_by')
                ->references('id')->on('users')->nullOnDelete();

            $table->foreign('deleted_by', 'fk_experiences_deleted_by')
                ->references('id')->on('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // ✅ Index
            $table->index('resume_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
