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
        // 🔥 FK issues avoid करने के लिए
        Schema::disableForeignKeyConstraints();

        // 🔥 अगर table already है तो delete
        if (Schema::hasTable('technical_skills')) {
            Schema::drop('technical_skills');
        }

        // 🔥 वापस enable
        Schema::enableForeignKeyConstraints();

        // ✅ Fresh create
        Schema::create('technical_skills', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('resume_id');
            $table->foreign('resume_id', 'fk_tech_skills_resume')
                ->references('id')
                ->on('resumes')
                ->cascadeOnDelete();

            $table->string('category')->nullable();
            $table->string('skill_name')->nullable();

            $table->text('icon_path')->nullable();
            $table->string('icon_viewbox')->default('0 0 24 24');
            $table->string('icon_fill')->default('#4e73df');

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('created_by', 'fk_tech_skills_created_by')
                ->references('id')->on('users')->nullOnDelete();

            $table->foreign('updated_by', 'fk_tech_skills_updated_by')
                ->references('id')->on('users')->nullOnDelete();

            $table->foreign('deleted_by', 'fk_tech_skills_deleted_by')
                ->references('id')->on('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_skills');
    }
};
