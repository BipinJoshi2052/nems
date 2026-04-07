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
        foreach (['staff', 'students', 'parent_users'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->text('address')->nullable();
                $table->string('facebook_url')->nullable();
                $table->string('x_url')->nullable();
                $table->string('linkedin_url')->nullable();
                $table->string('instagram_url')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['staff', 'students', 'parent_users'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn(['address', 'facebook_url', 'x_url', 'linkedin_url', 'instagram_url']);
            });
        }
    }
};
