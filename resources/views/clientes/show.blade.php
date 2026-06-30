<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle del Cliente') }}
            </h2>
            <a href="{{ route('prestamos.create', $cliente->id) }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                Nuevo Préstamo
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <!-- Datos del cliente -->
                <h3 class="text-lg font-semibold mb-4">Datos del Cliente</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <p><strong>Nombre:</strong> {{ strtoupper($cliente->nombre) }} {{ strtoupper($cliente->apellido) }}
                    </p>

                    <p><strong>DNI:</strong> {{ $cliente->dni }}</p>

                    <p><strong>Sexo:</strong> {{ $cliente->sexo == 'M' ? 'Masculino' : 'Femenino' }}</p>

                    <p><strong>Fecha de nacimiento:</strong>
                        {{ \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m/Y') }}</p>

                    <p><strong>Celular:</strong> {{ $cliente->celular }}</p>

                    <p><strong>Correo:</strong> {{ strtoupper($cliente->correo) }}</p>

                    <p><strong>Departamento:</strong> {{ $cliente->departamento->nombre }}</p>

                    <p><strong>Provincia:</strong> {{ $cliente->provincia->nombre }}</p>

                    <p><strong>Distrito:</strong> {{ $cliente->distrito->nombre }}</p>

                    <p><strong>Dirección:</strong> {{ $cliente->direccion }}</p>

                    <p><strong>Referencia:</strong> {{ $cliente->referencia ?? '—' }}</p>

                    <p><strong>Estado:</strong> {{ ucfirst($cliente->estado) }}</p>

                    @if ($cliente->adjuntos)
                        <p>
                            <strong>Recibo:</strong>
                            <a href="{{ asset('storage/' . $cliente->adjuntos->recibo_ruta) }}" download>
                                📥 Descargar
                            </a>
                        </p>

                        <p>
                            <strong>DNI:</strong>
                            <a href="{{ asset('storage/' . $cliente->adjuntos->dni_ruta) }}" download>
                                📥 Descargar
                            </a>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Tabla de cuotas -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Cuotas de Préstamos</h3>
                <table class="min-w-full border border-gray-200 rounded-md">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Fecha Préstamo</th>
                            <th class="px-4 py-2 text-left">Monto Préstamo</th>
                            <th class="px-4 py-2 text-left">Fecha Cuota</th>
                            <th class="px-4 py-2 text-left">Monto Cuota</th>
                            <th class="px-4 py-2 text-left">Mora</th>
                            <th class="px-4 py-2 text-left">Monto Abonado</th>
                            <th class="px-4 py-2 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cliente->prestamos as $prestamo)
                            @foreach ($prestamo->cuotas as $cuota)
                                @php
                                    $montoCuota = $cuota->monto;
                                    $mora = $cuota->mora ?? 0;
                                    $abonado = $cuota->monto_abono ?? 0;
                                @endphp

                                @if ($montoCuota + $mora == $abonado)
                                    @continue
                                @endif

                                <tr class="border-t">
                                    <td class="px-4 py-2">{{ $prestamo->fecha->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">{{ $prestamo->moneda }}
                                        {{ number_format($prestamo->monto, 2) }}</td>
                                    <td class="px-4 py-2">{{ $cuota->fecha->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">{{ $cuota->moneda }} {{ number_format($cuota->monto, 2) }}
                                    </td>
                                    <td class="px-4 py-2">{{ $cuota->moneda }} {{ $cuota->mora }}</td>
                                    <td class="px-4 py-2">
                                        {{ $cuota->monto_abono ? number_format($cuota->monto_abono, 2) : '-' }}</td>
                                    <td class="px-4 py-2 space-x-2">
                                        <!-- Botón pagar -->
                                        <a href="{{ route('cuotas.pagar', $cuota->id) }}"
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                            Pagar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
