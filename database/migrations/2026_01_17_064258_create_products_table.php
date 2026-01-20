<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('supplier_id')->nullable()->constrained();
            $table->string('name');
            $table->string('barcode')->unique();
            $table->decimal('cost_price', 10, 2); // តម្លៃដើម
            $table->decimal('sale_price', 10, 2); // តម្លៃលក់
            $table->integer('qty')->default(0);   // ចំនួនក្នុងស្តុក
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
