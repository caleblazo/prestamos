<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Capital;
use App\Models\Ingreso;
use App\Models\Egreso;
use App\Models\Prestamo;
use App\Models\Cuota;
use App\Models\Ganancia;
use App\Models\Abono;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Saldo actual Soles
        $ultimoCapital = Capital::where('moneda', 'PEN')
            ->orderBy('id', 'desc')
            ->first();
        $saldoActual = $ultimoCapital ? $ultimoCapital->monto : 0;
        $fechaSaldo = $ultimoCapital ? $ultimoCapital->created_at : null;

        // Saldo actual en dólares
        $ultimoCapitalDOL = Capital::where('moneda', 'DOL')
            ->orderBy('id', 'desc')
            ->first();

        $saldoActualDOL = $ultimoCapitalDOL ? $ultimoCapitalDOL->monto : 0;
        $fechaSaldoDOL = $ultimoCapitalDOL ? $ultimoCapitalDOL->created_at : null;

        // Préstamos
        $prestamosActivos = Prestamo::where('estado', 'no pagado')->count();

        // Movimientos recientes
        $ultimosPagos = Cuota::whereNotNull('fecha_abono')->latest()->take(5)->get();
        $ultimosPrestamos = Prestamo::latest()->take(5)->get();

        // Cuotas próximas a vencer (7 días)
        $proximasCuotas = Cuota::whereNull('fecha_abono')
            ->whereBetween('fecha', [Carbon::now(), Carbon::now()->addDays(7)])
            ->get();

        // Cuotas vencidas
        $cuotasVencidas = Cuota::whereNull('fecha_abono')
            ->where('fecha', '<', Carbon::now())
            ->get();

        // ============================
// CAPITAL TOTAL EN SOLES (PEN)
// ============================

// 1. Último capital registrado en PEN
$ultimoCapitalPEN = Capital::where('moneda', 'PEN')
    ->orderBy('id', 'desc')
    ->value('monto') ?? 0;

// 2. Suma de todos los préstamos pendientes en PEN
$totalPrestamosPendientesPEN = Prestamo::where('estado', 'no pagado')
    ->where('moneda', 'PEN')
    ->sum('monto');

// 3. Capital total final
$capitalTotalSoles = $ultimoCapitalPEN + $totalPrestamosPendientesPEN;

// 4. Fecha del último movimiento de capital
$fechaGuardado = Capital::orderBy('id', 'desc')->value('created_at');

        // ============================
        // GANANCIAS ÚLTIMOS 10 MESES
        // ============================

        $meses = collect();
        $gananciasMensuales = collect();

        for ($i = 9; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);

            // Mes en español
            $mes = $fecha->locale('es')->translatedFormat('F Y');

            $meses->push($mes);

            $ganancia = Ganancia::whereYear('fecha', $fecha->year)
                ->whereMonth('fecha', $fecha->month)
                ->sum('monto');

            $gananciasMensuales->push($ganancia);
        }

        // ============================
        // ABONOS DE CUOTAS PENDIENTES
        // ============================

        // Cuotas que NO están pagadas completamente
        $cuotasPendientesIds = Cuota::whereColumn('monto_abono', '<', DB::raw('monto + mora'))
            ->pluck('id');

        // Suma de abonos de esas cuotas
        $totalAbonosCuotasPendientes = Abono::whereIn('cuota_id', $cuotasPendientesIds)
            ->sum('monto');

        // ============================
        // ÚLTIMAS 10 TRANSACCIONES
        // ============================

        $transacciones = Capital::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'saldoActual',
            'fechaSaldo',
            'prestamosActivos',
            'ultimosPagos',
            'ultimosPrestamos',
            'proximasCuotas',
            'cuotasVencidas',
            'capitalTotalSoles',
            'fechaGuardado',
            'meses',
            'gananciasMensuales',
            'totalAbonosCuotasPendientes',
            'saldoActualDOL',
            'fechaSaldoDOL',
            'transacciones'
        ));
    }
}
