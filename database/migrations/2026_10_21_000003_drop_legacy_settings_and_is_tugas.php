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
        if (Schema::hasColumn('users', 'is_tugas')) {
            $indexes = collect(Schema::getIndexes('users'))->pluck('name');

            if ($indexes->contains('idx_users_is_tugas')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropIndex('idx_users_is_tugas');
                });
            }

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_tugas');
            });
        }

        Schema::dropIfExists('settings');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('users', 'is_tugas')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_tugas')->default(false);
            });
        }

        if (! Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }
    }
};
