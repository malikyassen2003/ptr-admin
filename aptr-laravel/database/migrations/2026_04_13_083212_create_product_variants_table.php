<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('product_variants', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_color_id')->constrained()->cascadeOnDelete();
        $table->string('label');
        $table->decimal('price', 8, 2);
        $table->unsignedInteger('stock')->default(0);
        $table->timestamps();
    });
}
};
