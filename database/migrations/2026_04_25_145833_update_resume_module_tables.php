<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. UNIQUE: resume_drafts.resume_id
        |--------------------------------------------------------------------------
        */
        if (!$this->indexExists('resume_drafts', 'resume_drafts_resume_id_unique')) {
            Schema::table('resume_drafts', function (Blueprint $table) {
                $table->unique('resume_id', 'resume_drafts_resume_id_unique');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | 2. FOREIGN KEYS
        |--------------------------------------------------------------------------
        */

        // educations
        if (!$this->foreignExists('educations', 'educations_resume_id_foreign')) {
            Schema::table('educations', function (Blueprint $table) {
                $table->foreign('resume_id')
                    ->references('id')->on('resumes')
                    ->onDelete('cascade');
            });
        }

        // technical_skills
        if (!$this->foreignExists('technical_skills', 'technical_skills_resume_id_foreign')) {
            Schema::table('technical_skills', function (Blueprint $table) {
                $table->foreign('resume_id')
                    ->references('id')->on('resumes')
                    ->onDelete('cascade');
            });
        }

        // experiences
        if (!$this->foreignExists('experiences', 'experiences_resume_id_foreign')) {
            Schema::table('experiences', function (Blueprint $table) {
                $table->foreign('resume_id')
                    ->references('id')->on('resumes')
                    ->onDelete('cascade');
            });
        }

        // experience_details
        if (!$this->foreignExists('experience_details', 'experience_details_experience_id_foreign')) {
            Schema::table('experience_details', function (Blueprint $table) {
                $table->foreign('experience_id')
                    ->references('id')->on('experiences')
                    ->onDelete('cascade');
            });
        }

        // resume_drafts FK
        if (!$this->foreignExists('resume_drafts', 'resume_drafts_resume_id_foreign')) {
            Schema::table('resume_drafts', function (Blueprint $table) {
                $table->foreign('resume_id')
                    ->references('id')->on('resumes')
                    ->onDelete('cascade');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | 3. INDEXES
        |--------------------------------------------------------------------------
        */

        if (!$this->indexExists('educations', 'educations_resume_id_index')) {
            Schema::table('educations', function (Blueprint $table) {
                $table->index('resume_id');
            });
        }

        if (!$this->indexExists('technical_skills', 'technical_skills_resume_id_index')) {
            Schema::table('technical_skills', function (Blueprint $table) {
                $table->index('resume_id');
            });
        }

        if (!$this->indexExists('experiences', 'experiences_resume_id_index')) {
            Schema::table('experiences', function (Blueprint $table) {
                $table->index('resume_id');
            });
        }
    }

    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | DROP SAFE
        |--------------------------------------------------------------------------
        */

        // resume_drafts
        if ($this->indexExists('resume_drafts', 'resume_drafts_resume_id_unique')) {
            Schema::table('resume_drafts', function (Blueprint $table) {
                $table->dropUnique('resume_drafts_resume_id_unique');
            });
        }

        if ($this->foreignExists('resume_drafts', 'resume_drafts_resume_id_foreign')) {
            Schema::table('resume_drafts', function (Blueprint $table) {
                $table->dropForeign('resume_drafts_resume_id_foreign');
            });
        }

        // educations
        if ($this->foreignExists('educations', 'educations_resume_id_foreign')) {
            Schema::table('educations', function (Blueprint $table) {
                $table->dropForeign('educations_resume_id_foreign');
            });
        }

        if ($this->indexExists('educations', 'educations_resume_id_index')) {
            Schema::table('educations', function (Blueprint $table) {
                $table->dropIndex('educations_resume_id_index');
            });
        }

        // technical_skills
        if ($this->foreignExists('technical_skills', 'technical_skills_resume_id_foreign')) {
            Schema::table('technical_skills', function (Blueprint $table) {
                $table->dropForeign('technical_skills_resume_id_foreign');
            });
        }

        if ($this->indexExists('technical_skills', 'technical_skills_resume_id_index')) {
            Schema::table('technical_skills', function (Blueprint $table) {
                $table->dropIndex('technical_skills_resume_id_index');
            });
        }

        // experiences
        if ($this->foreignExists('experiences', 'experiences_resume_id_foreign')) {
            Schema::table('experiences', function (Blueprint $table) {
                $table->dropForeign('experiences_resume_id_foreign');
            });
        }

        if ($this->indexExists('experiences', 'experiences_resume_id_index')) {
            Schema::table('experiences', function (Blueprint $table) {
                $table->dropIndex('experiences_resume_id_index');
            });
        }

        // experience_details
        if ($this->foreignExists('experience_details', 'experience_details_experience_id_foreign')) {
            Schema::table('experience_details', function (Blueprint $table) {
                $table->dropForeign('experience_details_experience_id_foreign');
            });
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function indexExists($table, $indexName)
    {
        return count(DB::select("
            SHOW INDEX FROM {$table} WHERE Key_name = '{$indexName}'
        ")) > 0;
    }

    private function foreignExists($table, $foreignName)
    {
        return count(DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = '{$table}' 
            AND CONSTRAINT_NAME = '{$foreignName}'
        ")) > 0;
    }
};