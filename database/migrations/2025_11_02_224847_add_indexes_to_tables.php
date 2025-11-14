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
            // Index untuk query by user_id (frequently used in filters)
            $table->index('user_id', 'idx_tugas_user_id');
            
            // Index untuk query by status (frequently used in filters & approval flow)
            $table->index('status', 'idx_tugas_status');
            
            // Index untuk query by priority
            $table->index('priority', 'idx_tugas_priority');
            
            // Composite index untuk common query patterns
            $table->index(['user_id', 'status'], 'idx_tugas_user_status');
            $table->index(['status', 'created_at'], 'idx_tugas_status_created');
            
            // Index untuk date range queries
            $table->index('tanggal_mulai', 'idx_tugas_tanggal_mulai');
            $table->index('tanggal_selesai', 'idx_tugas_tanggal_selesai');
            
            // Index untuk soft deletes
            $table->index('deleted_at', 'idx_tugas_deleted_at');
        });

        Schema::table('users', function (Blueprint $table) {
            // Index untuk query by email (login)
            $table->index('email', 'idx_users_email');
            
            // Index untuk query by jabatan (role-based queries)
            $table->index('jabatan', 'idx_users_jabatan');
            
            // Index untuk is_tugas (task assignment queries)
            if (Schema::hasColumn('users', 'is_tugas')) {
                $table->index('is_tugas', 'idx_users_is_tugas');
            }
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Index untuk token lookup (API authentication)
            $table->index('tokenable_id', 'idx_tokens_tokenable_id');
            $table->index('tokenable_type', 'idx_tokens_tokenable_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropIndex('idx_tugas_user_id');
            $table->dropIndex('idx_tugas_status');
            $table->dropIndex('idx_tugas_priority');
            $table->dropIndex('idx_tugas_user_status');
            $table->dropIndex('idx_tugas_status_created');
            $table->dropIndex('idx_tugas_tanggal_mulai');
            $table->dropIndex('idx_tugas_tanggal_selesai');
            $table->dropIndex('idx_tugas_deleted_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_email');
            $table->dropIndex('idx_users_jabatan');
            if (Schema::hasColumn('users', 'is_tugas')) {
                $table->dropIndex('idx_users_is_tugas');
            }
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropIndex('idx_tokens_tokenable_id');
            $table->dropIndex('idx_tokens_tokenable_type');
        });
    }
};
