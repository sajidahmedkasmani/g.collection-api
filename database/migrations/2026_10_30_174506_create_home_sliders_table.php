<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('home_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();

            // 'image_only' ya 'custom'
            $table->string('content_type', 20)->default('custom');

            $table->string('image')->nullable();
            $table->string('mobile_image')->nullable();
            $table->string('secondary_image')->nullable();

            $table->string('background_color', 20)->nullable();
            $table->string('background_gradient', 255)->nullable();
            $table->string('text_color', 20)->default('#1A1A1A');
            $table->string('text_alignment', 20)->default('left');
            $table->string('image_position', 20)->default('right');

            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->string('secondary_button_text')->nullable();
            $table->string('secondary_button_url')->nullable();

            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();

            $table->integer('sort_order')->default(0);
            $table->integer('slide_duration')->default(5500); // ms
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_sliders');
    }
};