<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parent_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('student_parent', function (Blueprint $table) {
            $table->uuid('student_id');
            $table->uuid('parent_user_id');
            $table->boolean('is_primary_contact')->default(false);
            $table->string('relationship');
            $table->timestamps();

            $table->primary(['student_id', 'parent_user_id']);
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('parent_user_id')->references('id')->on('parent_users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_parent');
        Schema::dropIfExists('parent_users');
    }
};
