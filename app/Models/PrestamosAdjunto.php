<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestamosAdjunto extends Model
{
    use HasFactory;

    protected $table = 'prestamos_adjuntos';

    protected $fillable = [
        'prestamo_id',
        'contrato_ruta',
        'contrato_nombre',
        'deposito_ruta',
        'deposito_nombre',
    ];

    // Relación con Prestamo
    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class);
    }
}
