<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClientesAdjunto;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'sexo',
        'dni',
        'departamento_id',
        'provincia_id',
        'distrito_id',
        'direccion',
        'referencia',
        'celular',
        'correo',
        'estado',
    ];

    public function referente()
    {
        return $this->hasOne(Referente::class);
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }

    public function distrito()
    {
        return $this->belongsTo(Distrito::class);
    }

    public function adjuntos()
    {
        return $this->hasOne(ClientesAdjunto::class);
    }
}
