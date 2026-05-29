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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->date('issue_date');
            $table->enum('status', ['draft', 'issued'])->default('issued');

            // გამყიდველი
            $table->string('seller_name')->default('ICETECH');
            $table->string('seller_id_number', 50)->nullable();
            $table->string('seller_address')->nullable();
            $table->string('seller_phone')->nullable();
            $table->string('seller_email')->nullable();
            $table->string('seller_bank')->nullable();
            $table->string('seller_account')->nullable();

            // მყიდველი (სავალდებულო არ არის)
            $table->string('buyer_name')->nullable();
            $table->string('buyer_id_number', 50)->nullable();
            $table->string('buyer_address')->nullable();
            $table->string('buyer_phone')->nullable();
            $table->string('buyer_email')->nullable();
            $table->string('buyer_bank')->nullable();
            $table->string('buyer_account')->nullable();

            $table->text('notes')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
