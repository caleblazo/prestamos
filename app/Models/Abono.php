<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    use HasFactory;

    protected $table = 'abonos';

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

    public function adjunto()
    {
        return $this->hasOne(AbonoAdjunto::class);
    }
}
