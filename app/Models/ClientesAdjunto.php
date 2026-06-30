<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientesAdjunto extends Model
{
    use HasFactory;

    protected $table = 'clientes_adjuntos'; // 👈 nombre exacto de la tabla

    protected $fillable = [
        'cliente_id',
        'dni_ruta',
        'dni_nombre',
        'recibo_ruta',
        'recibo_nombre',
    ];

    // Relación con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
