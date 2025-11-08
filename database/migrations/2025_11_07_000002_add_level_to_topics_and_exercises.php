<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            if (!Schema::hasColumn('topics', 'level')) {
                $table->unsignedInteger('level')->default(1)->after('type');
                $table->index(['subject_id','level']);
            }
        });

        Schema::table('exercises', function (Blueprint $table) {
            if (!Schema::hasColumn('exercises', 'level')) {
                $table->unsignedInteger('level')->default(1)->after('difficulty');
                $table->index(['topic_id','level']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            if (Schema::hasColumn('topics', 'level')) {
                $table->dropIndex(['subject_id','level']);
                $table->dropColumn('level');
            }
        });

        Schema::table('exercises', function (Blueprint $table) {
            if (Schema::hasColumn('exercises', 'level')) {
                $table->dropIndex(['topic_id','level']);
                $table->dropColumn('level');
            }
        });
    }
};

