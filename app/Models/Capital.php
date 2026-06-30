<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capital extends Model
{
    use HasFactory;

    protected $table = 'capitales';

    protected $fillable = [
        'prestamo_id',
        'cuota_id',
        'ingreso_id',
        'egreso_id',
        'moneda',
        'monto',
        'aumento',
        'descuento',
    ];

    protected $casts = [
        'monto'     => 'decimal:2',
        'aumento'   => 'decimal:2',
        'descuento' => 'decimal:2',
    ];

    // Relaciones
    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class);
    }

    public function cuota()
    {
        return $this->belongsTo(Cuota::class);
    }

    // Si tienes modelos Ingreso y Egreso:
    public function ingreso()
    {
        return $this->belongsTo(Ingreso::class);
    }

    public function egreso()
    {
        return $this->belongsTo(Egreso::class);
    }
}
