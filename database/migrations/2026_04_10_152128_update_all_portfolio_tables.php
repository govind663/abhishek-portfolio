<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function dropForeignIfExists($table, $foreignKey)
    {
        $dbName = DB::getDatabaseName();

        $exists = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = ? 
            AND TABLE_NAME = ? 
            AND CONSTRAINT_NAME = ?
        ", [$dbName, $table, $foreignKey]);

        if (!empty($exists)) {
            DB::statement("ALTER TABLE `$table` DROP FOREIGN KEY `$foreignKey`");
        }
    }

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        $tables = [
            'page_titles' => 'fk_page',
            'about_sections' => 'fk_about',
            'stats' => 'fk_stats',
            'skills' => 'fk_skills',
            'features' => 'fk_features',
        ];

        foreach ($tables as $tableName => $prefix) {

            // 🔥 Drop FK safely (custom names)
            $this->dropForeignIfExists($tableName, "{$prefix}_created_by");
            $this->dropForeignIfExists($tableName, "{$prefix}_updated_by");
            $this->dropForeignIfExists($tableName, "{$prefix}_deleted_by");

            Schema::table($tableName, function (Blueprint $table) {

                $table->unsignedBigInteger('created_by')->nullable()->change();
                $table->unsignedBigInteger('updated_by')->nullable()->change();
                $table->unsignedBigInteger('deleted_by')->nullable()->change();
            });

            // ✅ Recreate FK
            Schema::table($tableName, function (Blueprint $table) use ($prefix) {

                $table->foreign('created_by', "{$prefix}_created_by")
                    ->references('id')->on('users')->nullOnDelete();

                $table->foreign('updated_by', "{$prefix}_updated_by")
                    ->references('id')->on('users')->nullOnDelete();

                $table->foreign('deleted_by', "{$prefix}_deleted_by")
                    ->references('id')->on('users')->nullOnDelete();
            });
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        $tables = [
            'page_titles' => 'fk_page',
            'about_sections' => 'fk_about',
            'stats' => 'fk_stats',
            'skills' => 'fk_skills',
            'features' => 'fk_features',
        ];

        foreach ($tables as $tableName => $prefix) {

            $this->dropForeignIfExists($tableName, "{$prefix}_created_by");
            $this->dropForeignIfExists($tableName, "{$prefix}_updated_by");
            $this->dropForeignIfExists($tableName, "{$prefix}_deleted_by");
        }

        Schema::enableForeignKeyConstraints();
    }
};