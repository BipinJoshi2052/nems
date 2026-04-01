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
            $table->unsignedBigInteger('role_id')->nullable()->after('id');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->uuid('user_id')->nullable()->after('id');
        });

        Schema::table('parent_users', function (Blueprint $table) {
            $table->uuid('user_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role_id');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });

        Schema::table('parent_users', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
