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
        // 🔥 FK constraint issues avoid
        Schema::disableForeignKeyConstraints();

        // 🔥 अगर table exist करे तो drop
        Schema::dropIfExists('experience_details');

        // 🔥 enable वापस
        Schema::enableForeignKeyConstraints();

        // ✅ Fresh create
        Schema::create('experience_details', function (Blueprint $table) {
            $table->id();

            // ✅ Experience FK (manual name)
            $table->unsignedBigInteger('experience_id');
            $table->foreign('experience_id', 'fk_exp_details_experience')
                ->references('id')
                ->on('experiences')
                ->cascadeOnDelete();

            $table->text('description')->nullable();

            // 🔥 Status
            $table->enum('status', ['active', 'inactive'])->default('active');

            // ✅ Audit Columns
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            // 🔥 Audit FK (manual names)
            $table->foreign('created_by', 'fk_exp_details_created_by')
                ->references('id')->on('users')->nullOnDelete();

            $table->foreign('updated_by', 'fk_exp_details_updated_by')
                ->references('id')->on('users')->nullOnDelete();

            $table->foreign('deleted_by', 'fk_exp_details_deleted_by')
                ->references('id')->on('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // ✅ Index
            $table->index('experience_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experience_details');
    }
};
