<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void {
 Schema::create('orders',function(Blueprint $t){$t->id();$t->string('order_number')->unique();$t->string('customer_name');$t->string('email')->nullable();$t->string('phone',30);$t->text('address');$t->string('city',100);$t->string('payment_method',30);$t->unsignedBigInteger('subtotal_paisa');$t->string('status',30)->default('pending');$t->text('customer_note')->nullable();$t->timestamps();});
 Schema::create('order_items',function(Blueprint $t){$t->id();$t->foreignId('order_id')->constrained()->cascadeOnDelete();$t->foreignId('product_id')->constrained()->restrictOnDelete();$t->string('product_name');$t->unsignedBigInteger('unit_price_paisa');$t->unsignedInteger('quantity');$t->unsignedBigInteger('line_total_paisa');$t->timestamps();});
 } public function down(): void {Schema::dropIfExists('order_items');Schema::dropIfExists('orders');} };
