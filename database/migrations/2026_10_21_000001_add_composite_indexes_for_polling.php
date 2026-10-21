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
        Schema::table('tugas', function (Blueprint $table) {
            // Used by the approval badge and admin task list on every poll.
            $table->index(['created_by', 'status'], 'idx_tugas_creator_status');
        });

        Schema::table('task_comments', function (Blueprint $table) {
            // Used by the messages feed (comments not by me, per task owner).
            $table->index(['user_id', 'created_at'], 'idx_comments_user_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropIndex('idx_tugas_creator_status');
        });

        Schema::table('task_comments', function (Blueprint $table) {
            $table->dropIndex('idx_comments_user_created');
        });
    }
};
