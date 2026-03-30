<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('vertical', 32)->index();
            $table->decimal('price_monthly', 12, 2)->default(0);
            $table->unsignedInteger('max_students')->default(0);
            $table->unsignedInteger('storage_gb')->default(0);
            $table->unsignedInteger('trial_days')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
