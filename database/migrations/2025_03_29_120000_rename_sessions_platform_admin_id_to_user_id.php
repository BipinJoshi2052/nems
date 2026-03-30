<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Laravel's DatabaseSessionHandler always uses the column name "user_id"
     * (see Illuminate\Session\DatabaseSessionHandler::addUserInformation).
     */
    public function up(): void
    {
        if (Schema::hasColumn('sessions', 'platform_admin_id')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->renameColumn('platform_admin_id', 'user_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('sessions', 'user_id') && ! Schema::hasColumn('sessions', 'platform_admin_id')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->renameColumn('user_id', 'platform_admin_id');
            });
        }
    }
};
