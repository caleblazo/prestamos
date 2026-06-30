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
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('cuenta_empresa_id')->constrained('cuenta_empresas');
            $table->enum('porcentage', ['15', '20']);
            $table->char('moneda', 3);
            $table->decimal('monto', 10, 2);
            $table->integer('cuota');
            $table->date('fecha');
            $table->enum('estado', ['pagado', 'no pagado'])->default('no pagado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
