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
        Schema::create('ganancias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuota_id')->constrained('cuotas')->onDelete('cascade');
            $table->date('fecha');
            $table->char('moneda', 3);
            $table->decimal('monto', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ganancias');
    }
};
