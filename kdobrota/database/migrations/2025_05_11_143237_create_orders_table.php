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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Korisnik koji je naručio
            $table->string('status'); // Status narudžbine
            $table->decimal('total_amount', 10, 2); // Ukupan iznos
            $table->string('shipping_address'); // Adresa za dostavu
            $table->string('phone'); // Kontakt telefon
            $table->text('notes')->nullable(); // Napomene
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
