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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'user_type')) {
                $table->string('user_type')->nullable()->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'language_preference')) {
                $table->string('language_preference')->default('en')->after('user_type');
            }
            if (!Schema::hasColumn('users', 'thumbnail_id')) {
                $table->uuid('thumbnail_id')->nullable()->after('language_preference');
            }
            if (!Schema::hasColumn('users', 'original_id')) {
                $table->uuid('original_id')->nullable()->after('thumbnail_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_verified_at',
                'user_type',
                'language_preference',
                'thumbnail_id',
                'original_id'
            ]);
        });
    }
};
