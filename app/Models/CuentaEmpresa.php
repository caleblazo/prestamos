<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaEmpresa extends Model
{
    use HasFactory;

    protected $table = 'cuenta_empresas';

    protected $fillable = [
        'cuenta_bancaria',
        'cuenta_interbancaria',
        'entidad',
    ];

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'cuenta_empresa_id');
    }
}
