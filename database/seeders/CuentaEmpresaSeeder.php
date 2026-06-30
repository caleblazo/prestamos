<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CuentaEmpresa;

class CuentaEmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cuentaEmpresas = array_map('str_getcsv', file(storage_path('app/data/cuenta_empresas.csv')));
        array_shift($cuentaEmpresas);
        foreach ($cuentaEmpresas as $row) {
            CuentaEmpresa::create([
                'id' => $row[0],
                'cuenta_bancaria'      => $row[1],
                'cuenta_interbancaria' => $row[2],
                'entidad'              => $row[3],
            ]);
        }
    }
}
