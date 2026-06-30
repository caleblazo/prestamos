<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    use HasFactory;

    protected $table = 'cuotas';

    protected $fillable = [
        'prestamo_id',
        'numero_cuota',
        'fecha',
        'moneda',
        'monto',
        'fecha_abono',
        'monto_abono',
        'mora',
    ];

    protected $casts = [
        'fecha'        => 'date',
        'fecha_abono'  => 'date',
        'monto'        => 'decimal:2',
        'monto_abono'  => 'decimal:2',
    ];

    // Relaciones
    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class);
    }

    public function abonos()
    {
        return $this->hasMany(Abono::class);
    }

    public function ganancia()
    {
        return $this->hasOne(Ganancia::class);
    }
}
