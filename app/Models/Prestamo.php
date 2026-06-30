<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Capital;

class Prestamo extends Model
{
    use HasFactory;

    protected $table = 'prestamos';

    protected $fillable = [
        'cliente_id',
        'cuenta_empresa_id',
        'porcentage',
        'moneda',
        'monto',
        'cuota',
        'fecha',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function cuentaEmpresa()
    {
        return $this->belongsTo(CuentaEmpresa::class, 'cuenta_empresa_id');
    }

    public function cuotas()
    {
        return $this->hasMany(Cuota::class);
    }

    public function capitales()
    {
        return $this->hasMany(Capital::class);
    }
}
