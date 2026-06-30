<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuota;
use App\Models\Abono;
use App\Models\AbonoAdjunto;
use App\Models\Ganancia;
use App\Models\Capital;
use App\Models\Cliente;
use Illuminate\Support\Str;

class CuotaController extends Controller
{

    public function pagarVista(Cuota $cuota, Cliente $cliente)
    {
        return view('cuota.pagar', compact('cuota'));
    }

    public function pagarCuota(Request $request, Cuota $cuota)
    {
        $request->validate([
            'monto_abono' => 'required|numeric|min:0.1',
            'fecha_abono' => 'required|date',
            'voucher'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $prestamo = $cuota->prestamo;

        $cliente = $cuota->prestamo->cliente;

        // Calcular deuda actual
        $monto = $cuota->monto;
        $mora = $cuota->mora ?? 0;
        $abonado = $cuota->monto_abono ?? 0;

        $deudaActual = ($monto + $mora) - $abonado;

        // Registrar abono
        $abono = Abono::create([
            'cuota_id' => $cuota->id,
            'fecha'    => $request->fecha_abono,
            'moneda'   => 'PEN',
            'monto'    => $request->monto_abono,
        ]);

        // Guardar voucher usando el disco "public" que apunta a /data gracias al symlink
        $voucherPath = $request->file('voucher')->store('cuota', 'public');

        AbonoAdjunto::create([
            'abono_id' => $abono->id,
            'pago_ruta' => $voucherPath,        // ejemplo: cuota/archivo.jpeg
            'pago_nombre' => basename($voucherPath),
        ]);

        // Actualizar cuota
        $cuota->fecha_abono = $request->fecha_abono;
        $cuota->monto_abono = ($cuota->monto_abono ?? 0) + $request->monto_abono;
        $cuota->save();

        // Verificar si la cuota quedó pagada
        $totalPagado = $cuota->monto_abono;
        $totalDeuda = $cuota->monto + $mora;

        // Si la cuota quedó completamente pagada
        if ($totalPagado >= $totalDeuda) {

            // Calcular interés total del préstamo
            $interesTotal = $prestamo->monto * ($prestamo->porcentage / 100);

            // Registrar ganancia (35% del interés de esta cuota)
            Ganancia::create([
                'cuota_id' => $cuota->id,
                'fecha'    => now(),
                'moneda'   => 'PEN',
                'monto'    => $interesTotal * 0.75,
            ]);

            // Registrar capital (65% del interés de esta cuota)
            $ultimoCapital = Capital::where('moneda', 'PEN')
                ->orderBy('id', 'desc')
                ->first();

            $saldoAnterior = $ultimoCapital ? $ultimoCapital->monto : 0;

            $capitalRestante = $monto - $interesTotal;

            $nuevoSaldo = $saldoAnterior + $capitalRestante + ($interesTotal * 0.25) + $mora;

            Capital::create([
                'cuota_id'    => $cuota->id,
                'moneda'      => 'PEN',
                'monto'       => $nuevoSaldo,
                'aumento'     => ($interesTotal * 0.25) + $mora + $capitalRestante,
                'descuento'   => null,
            ]);

            // Verificar si todas las cuotas están pagadas
            $totalAbonadoPrestamo = $prestamo->cuotas->sum('monto_abono');
            $totalMoraPrestamo = $prestamo->cuotas->sum('mora');
            $totalAPagarPrestamo = $prestamo->monto + $totalMoraPrestamo;

            if ($totalAbonadoPrestamo >= $totalAPagarPrestamo) {
                $prestamo->estado = 'pagado';
                $prestamo->save();
            }
        }

        return redirect()
            ->route('clientes.show', $cliente->id)
            ->with('success', 'Pago registrado correctamente.');
    }
}
