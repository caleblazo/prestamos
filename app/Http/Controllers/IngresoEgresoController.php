<?php

namespace App\Http\Controllers;

use App\Models\Capital;
use App\Models\Egreso;
use App\Models\Ingreso;
use Illuminate\Http\Request;

class IngresoEgresoController extends Controller
{
    // ============================
    //        INGRESO
    // ============================
    public function ingreso(Request $request)
    {
        $request->validate([
            'monto' => 'required|numeric|min:0.1',
            'comentario' => 'required|string',
            'moneda' => 'required|in:PEN,DOL',
        ]);

        // Registrar ingreso
        $ingreso = Ingreso::create([
            'moneda' => $request->moneda,
            'monto' => $request->monto,
            'comentario' => $request->comentario,
        ]);

        // Obtener último capital de esa moneda
        $ultimoCapital = Capital::where('moneda', $request->moneda)
            ->orderBy('id', 'desc')
            ->first();

        $saldoAnterior = $ultimoCapital ? $ultimoCapital->monto : 0;

        // Nuevo saldo
        $nuevoSaldo = $saldoAnterior + $request->monto;

        // Registrar en capital
        Capital::create([
            'ingreso_id' => $ingreso->id,
            'moneda' => $request->moneda,
            'monto' => $nuevoSaldo,
            'aumento' => $request->monto,
            'descuento' => null,
        ]);

        return back()->with('success', 'Ingreso registrado correctamente.');
    }

    // ============================
    //        EGRESO
    // ============================
    public function egreso(Request $request)
    {
        $request->validate([
            'monto' => 'required|numeric|min:0.1',
            'comentario' => 'required|string',
            'moneda' => 'required|in:PEN,DOL',
        ]);

        // Registrar egreso
        $egreso = Egreso::create([
            'moneda' => $request->moneda,
            'monto' => $request->monto,
            'comentario' => $request->comentario,
        ]);

        // Obtener último capital de esa moneda
        $ultimoCapital = Capital::where('moneda', $request->moneda)
            ->orderBy('id', 'desc')
            ->first();

        $saldoAnterior = $ultimoCapital ? $ultimoCapital->monto : 0;

        // Nuevo saldo
        $nuevoSaldo = $saldoAnterior - $request->monto;

        // Registrar en capital
        Capital::create([
            'egreso_id' => $egreso->id,
            'moneda' => $request->moneda,
            'monto' => $nuevoSaldo,
            'aumento' => null,
            'descuento' => $request->monto,
        ]);

        return back()->with('success', 'Egreso registrado correctamente.');
    }
}
