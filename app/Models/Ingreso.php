<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    use HasFactory;

    protected $table = 'ingresos';

    protected $fillable = [
        'moneda',
        'monto',
        'comentario',
    ];

    public function capital()
    {
        return $this->hasOne(Capital::class);
    }
}
