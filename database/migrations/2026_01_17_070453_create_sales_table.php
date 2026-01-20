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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // អ្នកគិតលុយ
            $table->foreignId('customer_id')->nullable()->constrained(); // អតិថិជន (បើមាន)
            $table->string('invoice_number')->unique(); // លេខវិក្កយបត្រ INV-001
            $table->decimal('total_amount', 12, 2);     // សរុបបឋម
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('final_total', 12, 2);      // សរុបត្រូវបង់
            $table->string('payment_type');             // Cash/QR
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
        Schema::dropIfExists('sales');
    }
};
