<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;

class UbigeoSeeder extends Seeder
{

    public function run(): void
    {
        // Departamentos
        $departamentos = array_map('str_getcsv', file(storage_path('app/data/departamentos.csv')));
        array_shift($departamentos);
        foreach ($departamentos as $row) {
            Departamento::create([
                'id' => $row[0],
                'nombre' => $row[1],
            ]);
        }

        // Provincias
        $provincias = array_map('str_getcsv', file(storage_path('app/data/provincias.csv')));
        array_shift($provincias);
        foreach ($provincias as $row) {
            Provincia::create([
                'id' => $row[0],
                'nombre' => $row[2],
                'departamento_id' => $row[1],
            ]);
        }

        // Distritos
        $distritos = array_map('str_getcsv', file(storage_path('app/data/distritos.csv')));
        array_shift($distritos);
        foreach ($distritos as $row) {
            Distrito::create([
                'id' => $row[0],
                'nombre' => $row[2],
                'provincia_id' => $row[1],
            ]);
        }
    }
}
