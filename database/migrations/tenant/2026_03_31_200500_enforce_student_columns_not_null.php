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
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'class_id')) {
                DB::table('students')->whereNull('class_id')->delete();
                $table->uuid('class_id')->nullable(false)->change();
            }
            if (Schema::hasColumn('students', 'academic_year_id')) {
                DB::table('students')->whereNull('academic_year_id')->delete();
                $table->uuid('academic_year_id')->nullable(false)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'class_id')) {
                $table->uuid('class_id')->nullable()->change();
            }
            if (Schema::hasColumn('students', 'academic_year_id')) {
                $table->uuid('academic_year_id')->nullable()->change();
            }
        });
    }
};
