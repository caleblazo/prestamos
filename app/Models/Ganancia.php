<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ganancia extends Model
{
    use HasFactory;

    protected $table = 'ganancias';

    protected $fillable = [
        'cuota_id',
        'fecha',
        'moneda',
        'monto',
    ];

    public function cuota()
    {
        return $this->belongsTo(Cuota::class);
    }
}
