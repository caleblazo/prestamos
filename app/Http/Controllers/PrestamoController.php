<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Prestamo;
use App\Models\Cuota;
use App\Models\CuentaEmpresa;
use App\Models\CuentaCliente;
use App\Models\Capital;
use App\Models\PrestamosAdjunto;
use App\Models\ClientesAdjunto;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    public function create(Cliente $cliente)
    {
        $cuentasEmpresa = CuentaEmpresa::all();
        $cuentasCliente = CuentaCliente::where('cliente_id', $cliente->id)->get();

        return view('prestamos.create', compact('cliente', 'cuentasEmpresa', 'cuentasCliente'));
    }

    public function store(Request $request, Cliente $cliente)
    {

        $validated = $request->validate([
            'cuenta_empresa_id' => 'required|exists:cuenta_empresas,id',
            'monto'             => 'required|numeric|min:1',
            'cuota'             => 'required|integer|min:1',
            'fecha'             => 'required|date',
            'contrato'          => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'deposito'          => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
			'porcentage'        => 'required|numeric|min:1|max:100',
        ]);
		
		$porcentaje = $validated['porcentage'];

        // 📌 Validar que el cliente tenga adjuntos (DNI y recibo)
        $adjuntos = ClientesAdjunto::where('cliente_id', $cliente->id)->first();

        if (!$adjuntos || !$adjuntos->dni_ruta || !$adjuntos->recibo_ruta) {
            return redirect()
                ->route('prestamos.create', $cliente->id)
                ->with('error', 'El cliente debe tener registrado su DNI y su recibo antes de solicitar un préstamo.');
        }

        // 👉 Lógica de cuenta cliente
        if ($request->cuenta_opcion === 'nueva') {
            $codigoEntidad = substr($request->cuenta_interbancaria, 0, 3);
            switch ($codigoEntidad) {
                case "002":
                    $entidad = "BCP";
                    break;
                case "003":
                    $entidad = "IBK";
                    break;
                case "011":
                    $entidad = "BBVA";
                    break;
                case "009":
                    $entidad = "SBK";
                    break;
                case "038":
                    $entidad = "BANBIF";
                    break;
                default:
                    $entidad = "DESCONOCIDO";
            }

            $cuentaCliente = CuentaCliente::create([
                'cliente_id'          => $cliente->id,
                'cuenta_bancaria'     => $request->cuenta_bancaria,
                'cuenta_interbancaria' => $request->cuenta_interbancaria,
                'entidad'             => $entidad,
            ]);

            $cuentaClienteId = $cuentaCliente->id;
        } else {
            $cuentaClienteId = $request->cuenta_cliente_id;
        }

        // Calcular porcentaje
        $porcentaje = $request->porcentage;

        // Crear préstamo
        $prestamo = Prestamo::create([
            'cliente_id'        => $cliente->id,
            'cuenta_empresa_id' => $validated['cuenta_empresa_id'],
            'porcentage'        => (string) $porcentaje,
            'moneda'            => 'PEN',
            'monto'             => $validated['monto'],
            'cuota'             => $validated['cuota'],
            'fecha'             => $validated['fecha'],
            'estado'            => 'no pagado',
            'cuenta_cliente_id' => $cuentaClienteId, // 👈 ahora asociamos la cuenta cliente
        ]);

        $request->validate([
			'contrato' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
			'deposito' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
		]);

        // Guardar usando el disco "prestamos"
        $contratoPath = $request->file('contrato')->store('prestamo', 'public');
        $depositoPath = $request->file('deposito')->store('prestamo', 'public');

        PrestamosAdjunto::create([
            'prestamo_id' => $prestamo->id,
            'contrato_ruta' => $contratoPath,        // ejemplo: prestamo/archivo.jpeg
            'contrato_nombre' => basename($contratoPath),
            'deposito_ruta' => $depositoPath,
            'deposito_nombre' => basename($depositoPath),
        ]);

        // Generar cuotas
        for ($i = 1; $i <= $validated['cuota']; $i++) {
            Cuota::create([
                'prestamo_id'   => $prestamo->id,
                'numero_cuota'  => $i,
                'fecha'         => \Carbon\Carbon::parse($validated['fecha'])->addDays(30 * $i),
                'moneda'        => 'PEN',
                'monto'         => ($validated['monto'] * $porcentaje / 100) + ($validated['monto'] / $validated['cuota']),
                'mora'          => 0,
            ]);
        }

        // 📌 Tomar el último capital registrado (global)
        $ultimoCapital = Capital::where('moneda', 'PEN')
            ->orderBy('id', 'desc')
            ->first();

        $saldoAnterior = $ultimoCapital ? $ultimoCapital->monto : 0;

        // Calcular nuevo saldo
        $nuevoSaldo = $saldoAnterior - $validated['monto'];

        // Crear registro de capital
        Capital::create([
            'prestamo_id'     => $prestamo->id,
            'moneda'      => 'PEN',
            'monto'       => $nuevoSaldo,        // 👈 aquí reflejas el saldo actual
            'descuento'   => $validated['monto'], // 👈 aquí registras el movimiento
        ]);

        // Redirigir con mensaje
        return redirect()
            ->route('clientes.show', $cliente->id)
            ->with('success', 'Préstamo creado correctamente.');
    }
}
