<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_collections', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('product_collections')->cascadeOnDelete();
            $table->string('name', 40);
            $table->string('slug');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['collection_id', 'slug']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->after('category')
                ->constrained('product_categories')
                ->nullOnDelete();
        });

        $collections = [
            'clothing' => 'Clothing',
            'bags' => 'Bags',
            'accessories' => 'Accessories',
        ];
        $collectionIds = [];

        foreach ($collections as $slug => $name) {
            $collectionIds[$slug] = DB::table('product_collections')->insertGetId([
                'name' => $name,
                'slug' => $slug,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $categoryMap = [
            'stitched' => [$collectionIds['clothing'], 'Stitched'],
            'unstitched' => [$collectionIds['clothing'], 'Unstitched'],
            'other' => [$collectionIds['clothing'], 'Other'],
            'accessories' => [$collectionIds['accessories'], 'Accessories'],
        ];

        foreach ($categoryMap as $slug => [$collectionId, $name]) {
            DB::table('product_categories')->insert([
                'collection_id' => $collectionId,
                'name' => $name,
                'slug' => $slug,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach (DB::table('products')->select('id', 'category')->get() as $product) {
            $categoryId = DB::table('product_categories')
                ->where('slug', strtolower((string) $product->category))
                ->value('id');

            if ($categoryId) {
                DB::table('products')->where('id', $product->id)->update(['category_id' => $categoryId]);
            }
        }

        foreach ([
            [$collectionIds['bags'], 'Handbags', 'handbags'],
            [$collectionIds['bags'], 'Backpacks', 'backpacks'],
            [$collectionIds['accessories'], 'Jewellery', 'jewellery'],
        ] as [$collectionId, $name, $slug]) {
            DB::table('product_categories')->insert([
                'collection_id' => $collectionId,
                'name' => $name,
                'slug' => $slug,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('product_collections');
    }
};
