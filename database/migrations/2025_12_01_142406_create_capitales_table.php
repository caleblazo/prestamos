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
        Schema::create('capitales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestamo_id')->nullable()->constrained('prestamos')->onDelete('set null');
            $table->foreignId('cuota_id')->nullable()->constrained('cuotas')->onDelete('set null');
            $table->foreignId('ingreso_id')->nullable()->constrained('ingresos')->onDelete('set null');
            $table->foreignId('egreso_id')->nullable()->constrained('egresos')->onDelete('set null');
            $table->char('moneda', 3);
            $table->decimal('monto', 10, 2);
            $table->decimal('aumento', 10, 2)->nullable();
            $table->decimal('descuento', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capitales');
    }
};
