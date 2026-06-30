<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pagar Cuota') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">

                <!-- Mensajes -->
                @if(session('success'))
                <div class="mb-4 text-green-600 font-semibold">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="mb-4 text-red-600 font-semibold">
                    {{ session('error') }}
                </div>
                @endif

                @if ($errors->any())
                <div class="mb-4">
                    <ul class="text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Información de la cuota -->
                <div class="mb-6">
                    <p><strong>Monto cuota:</strong> S/ {{ $cuota->monto }}</p>
                    <p><strong>Mora:</strong> S/ {{ $cuota->mora ?? 0 }}</p>
                    <p><strong>Abonado:</strong> S/ {{ $cuota->monto_abono ?? 0 }}</p>

                    <p class="mt-2 text-blue-600 font-bold">
                        <strong>Debe pagar:</strong>
                        S/ {{ ($cuota->monto + ($cuota->mora ?? 0)) - ($cuota->monto_abono ?? 0) }}
                    </p>
                </div>

                <!-- Formulario -->
                <form method="POST" action="{{ route('cuotas.pagar', $cuota->id) }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Monto a abonar -->
                    <div class="mb-4">
                        <x-label for="monto_abono" value="Monto a abonar" />
                        <x-input id="monto_abono" name="monto_abono" type="number" step="0.01" class="w-full" required />
                    </div>

                    <!-- Fecha de abono -->
                    <div class="mb-4">
                        <x-label for="fecha_abono" value="Fecha de abono" />
                        <x-input id="fecha_abono" name="fecha_abono" type="date" class="w-full" required />
                    </div>

                    <!-- Voucher -->
                    <div class="mb-4">
                        <x-label for="voucher" value="Voucher del abono" />
                        <x-input id="voucher" name="voucher" type="file" class="w-full" required />
                    </div>

                    <input type="hidden" name="moneda" value="PEN">

                    <!-- Botón -->
                    <div class="mt-6 flex justify-end">
                        <x-button class="bg-green-600 hover:bg-green-700 text-white">
                            Registrar Pago
                        </x-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>