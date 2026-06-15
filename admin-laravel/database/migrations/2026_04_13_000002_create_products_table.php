<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('brand');
            $table->string('name');
            $table->string('short_spec');
            $table->decimal('base_price', 8, 2);
            $table->decimal('original_price', 8, 2)->nullable();
            $table->unsignedTinyInteger('discount')->default(0);
            $table->string('badge')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_best_selling')->default(false);
            $table->boolean('has_variants')->default(false);
            $table->unsignedInteger('stock')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};