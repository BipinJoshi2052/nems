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
        // Update Users (Staff)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'thumbnail_id')) {
                $table->uuid('thumbnail_id')->nullable()->after('remember_token');
                $table->foreign('thumbnail_id')->references('id')->on('attachment_files')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'original_id')) {
                $table->uuid('original_id')->nullable()->after('thumbnail_id');
                $table->foreign('original_id')->references('id')->on('attachment_files')->onDelete('set null');
            }
        });

        // Update Students
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'thumbnail_id')) {
                $table->uuid('thumbnail_id')->nullable()->after('photo_attachment_id');
                $table->foreign('thumbnail_id')->references('id')->on('attachment_files')->onDelete('set null');
            }
            if (!Schema::hasColumn('students', 'original_id')) {
                $table->uuid('original_id')->nullable()->after('thumbnail_id');
                $table->foreign('original_id')->references('id')->on('attachment_files')->onDelete('set null');
            }
        });

        // Update Parents
        Schema::table('parents', function (Blueprint $table) {
            if (!Schema::hasColumn('parents', 'thumbnail_id')) {
                $table->uuid('thumbnail_id')->nullable()->after('relationship');
                $table->foreign('thumbnail_id')->references('id')->on('attachment_files')->onDelete('set null');
            }
            if (!Schema::hasColumn('parents', 'original_id')) {
                $table->uuid('original_id')->nullable()->after('thumbnail_id');
                $table->foreign('original_id')->references('id')->on('attachment_files')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['thumbnail_id']);
            $table->dropForeign(['original_id']);
            $table->dropColumn(['thumbnail_id', 'original_id']);
        });
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['thumbnail_id']);
            $table->dropForeign(['original_id']);
            $table->dropColumn(['thumbnail_id', 'original_id']);
        });
        Schema::table('parents', function (Blueprint $table) {
            $table->dropForeign(['thumbnail_id']);
            $table->dropForeign(['original_id']);
            $table->dropColumn(['thumbnail_id', 'original_id']);
        });
    }
};
