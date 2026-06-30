<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaCliente extends Model
{
    use HasFactory;

    protected $table = 'cuenta_clientes';

    protected $fillable = [
        'cliente_id',
        'cuenta_bancaria',
        'cuenta_interbancaria',
        'entidad',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
