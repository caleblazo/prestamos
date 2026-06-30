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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->date('fecha_nacimiento');
            $table->enum('sexo', ['F', 'M']);
            $table->char('dni', 8)->unique();
            $table->foreignId('departamento_id')->constrained('departamentos');
            $table->foreignId('provincia_id')->constrained('provincias');
            $table->foreignId('distrito_id')->constrained('distritos');
            $table->string('direccion');
            $table->string('referencia')->nullable();
            $table->char('celular', 9);
            $table->string('correo')->unique();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
