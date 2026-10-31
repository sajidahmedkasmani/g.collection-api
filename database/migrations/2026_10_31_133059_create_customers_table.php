<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_deleted')->default(false);
            $table->rememberToken();
            $table->softDeletes(); 
            $table->timestamps();

            // Email unique sirf non-deleted customers ke liye
            // MySQL: generated column trick
            $table->unique(['email', 'is_deleted']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};