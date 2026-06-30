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
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestamo_id')->constrained('prestamos')->onDelete('cascade');
            $table->integer('numero_cuota');
            $table->date('fecha');
            $table->char('moneda', 3);
            $table->decimal('monto', 10, 2);
            $table->date('fecha_abono')->nullable();
            $table->decimal('monto_abono', 10, 2)->nullable();
            $table->integer('mora')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuotas');
    }
};
