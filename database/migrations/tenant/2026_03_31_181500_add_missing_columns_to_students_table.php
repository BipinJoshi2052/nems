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
            if (!Schema::hasColumn('students', 'date_of_birth_ad')) {
                $table->date('date_of_birth_ad')->nullable()->after('admission_no');
            }
            if (!Schema::hasColumn('students', 'date_of_birth_bs')) {
                $table->string('date_of_birth_bs')->nullable()->after('date_of_birth_ad');
            }
            if (!Schema::hasColumn('students', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth_bs');
            }
            if (!Schema::hasColumn('students', 'photo_attachment_id')) {
                $table->uuid('photo_attachment_id')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('students', 'medical_notes')) {
                $table->text('medical_notes')->nullable()->after('photo_attachment_id');
            }
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth_ad', 'date_of_birth_bs', 'gender', 
                'photo_attachment_id', 'medical_notes', 'status', 
                'withdrawal_reason', 'withdrawn_at'
            ]);
        });
    }
};
