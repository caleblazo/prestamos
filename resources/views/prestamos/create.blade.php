<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Préstamo') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
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

                <!-- Formulario -->
                <form method="POST" action="{{ route('prestamos.store', $cliente->id) }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Cliente -->
                    <div class="mb-4">
                        <x-label value="Cliente" />
                        <x-input type="text" value="{{ strtoupper($cliente->nombre) }} {{ strtoupper($cliente->apellido) }}" readonly class="w-full bg-gray-100" />
                    </div>

                    <!-- Cuenta Empresa -->
                    <div class="mb-4">
                        <x-label for="cuenta_empresa_id" value="Cuenta Empresa" />
                        <select name="cuenta_empresa_id" id="cuenta_empresa_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            @foreach($cuentasEmpresa as $cuenta)
                            <option value="{{ $cuenta->id }}">
                                {{ $cuenta->entidad }} - {{ $cuenta->cuenta_bancaria }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cuenta Cliente -->
                    <div class="mb-6">
                        <x-label value="Cuenta Cliente" />

                        <div class="flex items-center gap-4 mb-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="cuenta_opcion" value="existente" checked class="form-radio text-indigo-600">
                                <span class="ml-2 text-sm text-gray-700">Usar cuenta existente</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="cuenta_opcion" value="nueva" class="form-radio text-indigo-600">
                                <span class="ml-2 text-sm text-gray-700">Crear nueva cuenta</span>
                            </label>
                        </div>

                        <!-- Lista de cuentas existentes -->
                        <div id="cuenta-existente" class="mt-2">
                            @if($cuentasCliente->count())
                            <select name="cuenta_cliente_id" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Seleccione una cuenta</option>
                                @foreach($cuentasCliente as $cuenta)
                                <option value="{{ $cuenta->id }}">
                                    {{ $cuenta->entidad }} - {{ $cuenta->cuenta_bancaria }} - {{ $cuenta->cuenta_interbancaria }}
                                </option>
                                @endforeach
                            </select>
                            @else
                            <p class="text-sm text-red-600">Este cliente no tiene cuentas registradas.</p>
                            @endif
                        </div>

                        <!-- Formulario para nueva cuenta -->
                        <div id="cuenta-nueva" class="mt-4 hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-label for="cuenta_bancaria" value="Cuenta Bancaria" />
                                    <x-input id="cuenta_bancaria" name="cuenta_bancaria" type="text" class="w-full" />
                                </div>
                                <div>
                                    <x-label for="cuenta_interbancaria" value="Cuenta Interbancaria" />
                                    <x-input id="cuenta_interbancaria" name="cuenta_interbancaria" type="text" class="w-full" />
                                </div>
                                <div>
                                    <x-label for="entidad" value="Entidad Detectada" />
                                    <x-input id="entidad" name="entidad" type="text" readonly class="w-full bg-gray-100" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monto -->
                    <div class="mb-4">
                        <x-label for="monto" value="Monto del Préstamo" />
                        <x-input id="monto" name="monto" type="number" step="0.01" class="w-full" required />
                    </div>

                    <!-- Porcentaje (calculado automáticamente con JS) -->
                    <div class="mb-4">
                        <x-label for="porcentage" value="Porcentaje" />
                        <x-input id="porcentage" name="porcentage" type="number" min="1" max="100" class="w-full" />

                    <!-- Moneda (automático soles) -->
                    <div class="mb-4">
                        <x-label for="moneda" value="Moneda" />
                        <x-input id="moneda" name="moneda" type="text" value="Soles" readonly class="w-full bg-gray-100" />
                    </div>

                    <!-- Cuotas -->
                    <div class="mb-4">
                        <x-label for="cuota" value="Número de Cuotas" />
                        <x-input id="cuota" name="cuota" type="number" min="1" class="w-full" required />
                    </div>

                    <!-- Fecha -->
                    <div class="mb-4">
                        <x-label for="fecha" value="Fecha del Préstamo" />
                        <x-input id="fecha" name="fecha" type="date" class="w-full" required />
                    </div>

                    <!-- Archivos Adjuntos -->
                    <div class="mb-4">
                        <x-label for="contrato" value="Contrato" />
                        <x-input id="contrato" name="contrato" type="file" class="w-full" required />
                    </div>

                    <div class="mb-4">
                        <x-label for="deposito" value="Depósito" />
                        <x-input id="deposito" name="deposito" type="file" class="w-full" required />
                    </div>

                    <!-- Botón -->
                    <div class="mt-6 flex justify-end">
                        <x-button class="bg-indigo-600 hover:bg-indigo-700 text-white">
                            Guardar Préstamo
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script para alternar opciones y detectar entidad -->
    <script>
        const radios = document.querySelectorAll('input[name="cuenta_opcion"]');
        const cuentaExistente = document.getElementById('cuenta-existente');
        const cuentaNueva = document.getElementById('cuenta-nueva');

        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'existente') {
                    cuentaExistente.classList.remove('hidden');
                    cuentaNueva.classList.add('hidden');
                } else {
                    cuentaExistente.classList.add('hidden');
                    cuentaNueva.classList.remove('hidden');
                }
            });
        });

        document.getElementById('cuenta_interbancaria').addEventListener('input', function() {
            let codigo = this.value.substring(0, 3);
            let entidad = 'DESCONOCIDO';

            switch (codigo) {
                case '002':
                    entidad = 'BCP';
                    break;
                case '003':
                    entidad = 'IBK';
                    break;
                case '011':
                    entidad = 'BBVA';
                    break;
                case '009':
                    entidad = 'SBK';
                    break;
                case '038':
                    entidad = 'BANBIF';
                    break;
            }

            document.getElementById('entidad').value = entidad;
        });
    </script>

</x-app-layout>