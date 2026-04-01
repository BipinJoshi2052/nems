<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('admission_no')->unique();
            $table->date('date_of_birth_ad')->nullable();
            $table->string('date_of_birth_bs')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->uuid('photo_attachment_id')->nullable();
            $table->text('medical_notes')->nullable();
            $table->enum('status', ['active', 'withdrawn', 'graduated'])->default('active');
            $table->string('withdrawal_reason')->nullable();
            $table->timestamp('withdrawn_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
