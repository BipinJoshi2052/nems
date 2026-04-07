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
        Schema::dropIfExists('password_histories');
        
        Schema::create('password_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->string('user_type');
            $table->string('password_hash');
            $table->timestamp('created_at')->nullable();
            
            $table->index(['user_id', 'user_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_histories');
    }
};
