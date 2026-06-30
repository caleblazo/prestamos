<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cobranza') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Tarjeta principal -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Clientes por cobrar</h3>

                @if($cuotas->isEmpty())
                <p class="text-gray-500">No hay cuotas próximas o vencidas.</p>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Cliente</th>
                                <th class="px-4 py-2 text-left">Celular</th>
                                <th class="px-4 py-2 text-left">Cuota</th>
                                <th class="px-4 py-2 text-left">Fecha</th>
                                <th class="px-4 py-2 text-left">Monto</th>
                                <th class="px-4 py-2 text-left">Mora</th>
                                <th class="px-4 py-2 text-left">Acción</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($cuotas as $cuota)
                            @php
                            $cliente = $cuota->prestamo->cliente;
                            $prestamo = $cuota->prestamo;

                            // Regla de mora según año
							$totalPagar = $cuota->monto + $cuota->mora;
                            $mensajeMora = $prestamo->fecha->year <= 2025
                                ? "S/ 5 por día de retraso"
                                : "0.5% diario del monto de la cuota" ;

                                // Mensaje de WhatsApp
                                $mensaje=urlencode( "*Préstamos L&H:*\n"
                                ."Estimado/a {$cliente->nombre} {$cliente->apellido},\n"
                                ."Tu cuota de {$cuota->moneda} {$totalPagar} "
                                ."con fecha de vencimiento {$cuota->fecha->format('d/m/Y')} "
                                ."ya está disponible para pago.\n"
                                ."La mora aplicable es: {$mensajeMora}.\n"
                                ."Si ya realizaste el pago, ignora este mensaje.\nGracias."
                                );

                                $whatsapp = "https://api.whatsapp.com/send/?phone=51{$cliente->celular}&text={$mensaje}";
                                @endphp

                                <tr class="border-t">
                                    <td class="px-4 py-2">{{ $cliente->nombre }} {{ $cliente->apellido }}</td>
                                    <td class="px-4 py-2">{{ $cliente->celular }}</td>
                                    <td class="px-4 py-2">{{ $cuota->numero_cuota }}</td>
                                    <td class="px-4 py-2">{{ $cuota->fecha->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">{{ $cuota->moneda }} {{ $cuota->monto }}</td>
                                    <td class="px-4 py-2">{{ $cuota->mora }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ $whatsapp }}" target="_blank"
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                            WhatsApp
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>