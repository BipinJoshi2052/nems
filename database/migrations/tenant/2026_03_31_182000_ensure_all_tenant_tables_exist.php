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
        // 1. Ensure 'students' has all columns (redundant but safe)
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                if (!Schema::hasColumn('students', 'status')) {
                    $table->enum('status', ['active', 'withdrawn', 'graduated'])->default('active')->after('medical_notes');
                }
                if (!Schema::hasColumn('students', 'withdrawal_reason')) {
                    $table->string('withdrawal_reason')->nullable()->after('status');
                }
                if (!Schema::hasColumn('students', 'withdrawn_at')) {
                    $table->timestamp('withdrawn_at')->nullable()->after('withdrawal_reason');
                }
            });
        }

        // 2. Ensure 'parents' table exists
        if (!Schema::hasTable('parents')) {
            Schema::create('parents', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->string('email')->unique()->nullable();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->enum('gender', ['male', 'female', 'other'])->nullable();
                $table->string('occupation')->nullable();
                $table->string('relationship')->nullable();
                $table->uuid('student_id')->nullable();
                $table->timestamps();
            });
        }

        // 3. Ensure 'enrollments' table exists
        if (!Schema::hasTable('enrollments')) {
            Schema::create('enrollments', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('student_id');
                $table->uuid('class_id');
                $table->uuid('academic_year_id');
                $table->string('section')->nullable();
                $table->string('roll_no')->nullable();
                $table->enum('promotion_status', ['pending', 'promoted', 'failed', 'transferred'])->default('pending');
                $table->timestamps();
            });
        }

        // 4. Ensure 'subjects' table exists
        if (!Schema::hasTable('subjects')) {
            Schema::create('subjects', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->string('code')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 5. Ensure 'classes' table exists
        if (!Schema::hasTable('classes')) {
            Schema::create('classes', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->uuid('academic_year_id');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration for a recovery fixer to avoid data loss
    }
};
