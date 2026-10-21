<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Soft-deleted rows keep their email today. Rename them first so the
        // unique index can be created without losing the row for audit.
        foreach (DB::table('users')->whereNotNull('deleted_at')->get(['id', 'email']) as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['email' => $user->email.'.deleted.'.$user->id]);
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasIndex('users', 'idx_users_email')) {
                $table->dropIndex('idx_users_email');
            }
            $table->unique('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
        });
    }
};
