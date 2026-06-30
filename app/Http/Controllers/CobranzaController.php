<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use Illuminate\Http\Request;

class CobranzaController extends Controller
{
    public function index()
    {
        $hoy = now()->startOfDay();

        $cuotas = Cuota::with(['prestamo.cliente'])
            ->where(function ($q) {
                $q->whereNull('monto_abono')
                    ->orWhereRaw('monto_abono < (monto + mora)');
            })
            ->whereDate('fecha', '<=', $hoy->copy()->addDays(7))
            ->whereDate('fecha', '>=', $hoy->copy()->subDays(30))
            ->get();

        return view('cobranza.index', compact('cuotas'));
    }
}
