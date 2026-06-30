<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    use HasFactory;

    protected $table = 'egresos';

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
