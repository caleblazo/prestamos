<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbonoAdjunto extends Model
{
    use HasFactory;

    protected $table = 'abonos_adjuntos';

    protected $fillable = [
        'abono_id',
        'pago_ruta',
        'pago_nombre',
    ];

    public function abono()
    {
        return $this->belongsTo(Abono::class);
    }
}
