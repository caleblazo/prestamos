<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Financiero') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Resumen -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                <div class="bg-white shadow rounded-lg p-6">
                    <p class="text-gray-500">Saldo Actual</p>
                    <p class="text-2xl font-bold text-green-600">S/ {{ number_format($saldoActual, 2) }}</p>

                    @if ($fechaSaldo)
                        <p class="text-xs text-gray-400 mt-1">
                            Actualizado: {{ $fechaSaldo->format('Y-m-d H:i:s') }}
                        </p>
                    @endif
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <p class="text-gray-500">Préstamos Activos</p>
                    <p class="text-2xl font-bold">{{ $prestamosActivos }}</p>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <p class="text-gray-500">Capital Total (S/)</p>
                    <p class="text-2xl font-bold text-purple-600">S/ {{ number_format($capitalTotalSoles, 2) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Actualizado: {{ $fechaGuardado }}</p>
                </div>

            </div>

            <!-- Más métricas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                <div class="bg-white shadow rounded-lg p-6">
                    <p class="text-gray-500">Abonos en Cuotas Pendientes</p>
                    <p class="text-2xl font-bold text-orange-600">
                        S/ {{ number_format($totalAbonosCuotasPendientes, 2) }}
                    </p>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <p class="text-gray-500">Saldo Actual (USD)</p>
                    <p class="text-2xl font-bold text-blue-600">
                        $ {{ number_format($saldoActualDOL, 2) }}
                    </p>

                    @if ($fechaSaldoDOL)
                        <p class="text-xs text-gray-400 mt-1">
                            Actualizado: {{ $fechaSaldoDOL->format('Y-m-d H:i:s') }}
                        </p>
                    @endif
                </div>

            </div>

            <!-- Gráfico de Ganancias Mensuales -->
            <div class="bg-white shadow rounded-lg p-6 mt-6 mb-10">
                <h3 class="font-semibold mb-4 text-gray-700 text-lg">Ganancias de los Últimos 10 Meses</h3>

                <div id="graficoGanancias" data-meses='@json($meses)'
                    data-ganancias='@json($gananciasMensuales)' class="w-full">
                    <canvas id="graficoGananciasCanvas" class="w-full h-[300px]"></canvas>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                const contenedor = document.getElementById('graficoGanancias');

                const meses = JSON.parse(contenedor.dataset.meses);
                const ganancias = JSON.parse(contenedor.dataset.ganancias);

                const ctx = document.getElementById('graficoGananciasCanvas').getContext('2d');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: meses,
                        datasets: [{
                            label: 'Ganancia (PEN)',
                            data: ganancias,
                            backgroundColor: 'rgba(99, 102, 241, 0.5)',
                            borderColor: 'rgba(99, 102, 241, 1)',
                            borderWidth: 2,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: value => 'S/ ' + value
                                }
                            }
                        }
                    }
                });
            </script>
            <!-- Tabla de Transacciones -->
            <div class="bg-white shadow rounded-lg p-6 mt-6 mb-10">
                <h3 class="font-semibold mb-4 text-gray-700 text-lg">Transacciones</h3>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Moneda</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Utilidad</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">

                        @foreach ($transacciones as $t)
                            <tr>
                                <!-- DESCRIPCIÓN -->
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    @if ($t->prestamo_id && !$t->cuota_id && !$t->ingreso_id && !$t->egreso_id)
                                        Se realizó un préstamo
                                    @elseif($t->cuota_id && !$t->prestamo_id && !$t->ingreso_id && !$t->egreso_id)
                                        Se realizó un pago de cuota
                                    @elseif($t->ingreso_id && !$t->prestamo_id && !$t->cuota_id && !$t->egreso_id)
                                        @if ($t->moneda === 'PEN')
                                            Se realizó un ingreso en soles
                                        @else
                                            Se realizó un ingreso en dólares
                                        @endif
                                    @elseif($t->egreso_id && !$t->prestamo_id && !$t->cuota_id && !$t->ingreso_id)
                                        @if ($t->moneda === 'PEN')
                                            Se realizó un retiro en soles
                                        @else
                                            Se realizó un retiro en dólares
                                        @endif
                                    @else
                                        Movimiento desconocido
                                    @endif
                                </td>

                                <!-- MONEDA -->
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    {{ $t->moneda }}
                                </td>

                                <!-- MONTO -->
                                <td class="px-4 py-2 text-sm font-semibold text-gray-900">
                                    {{ number_format($t->monto, 2) }}
                                </td>

                                <!-- UTILIDAD -->
                                <td class="px-4 py-2 text-sm">
                                    @if ($t->aumento)
                                        <span class="text-green-600 font-bold">
                                            +{{ number_format($t->aumento, 2) }} ↑
                                        </span>
                                    @elseif($t->descuento)
                                        <span class="text-red-600 font-bold">
                                            -{{ number_format($t->descuento, 2) }} ↓
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                <!-- FECHA -->
                                <td class="px-4 py-2 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($t->created_at)->format('Y-m-d H:i') }}
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
