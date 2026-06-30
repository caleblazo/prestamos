<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Ingreso / Egreso') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">

                <!-- Mensajes -->
                @if (session('success'))
                    <div class="mb-4 text-green-600 font-semibold">
                        {{ session('success') }}
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

                <!-- Selector -->
                <div class="mb-6">
                    <label class="font-semibold">Tipo de movimiento</label>
                    <select id="tipo" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="ingreso">Ingreso</option>
                        <option value="egreso">Egreso</option>
                    </select>
                </div>

                <!-- Formulario -->
                <form id="form-movimiento" method="POST" action="{{ route('ingreso.store') }}">
                    @csrf

                    <div class="mb-4">
                        <x-label for="moneda" value="Moneda" />

                        <select id="moneda" name="moneda"
                            class="w-full border-gray-300 rounded-md shadow-sm text-gray-700" required>

                            <option value="PEN">
                                🇵🇪 PEN - S/.
                            </option>

                            <option value="DOL">
                                🇺🇸 DOL - $/.
                            </option>

                        </select>
                    </div>

                    <div class="mb-4">
                        <x-label for="monto" value="Monto" />
                        <x-input id="monto" name="monto" type="number" step="0.01" class="w-full" required />
                    </div>

                    <div class="mb-4">
                        <x-label for="comentario" value="Comentario" />
                        <textarea id="comentario" name="comentario" class="w-full border-gray-300 rounded-md" required></textarea>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-button class="bg-indigo-600 hover:bg-indigo-700 text-white">
                            Registrar
                        </x-button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('tipo').addEventListener('change', function() {
            const form = document.getElementById('form-movimiento');
            if (this.value === 'ingreso') {
                form.action = "{{ route('ingreso.store') }}";
            } else {
                form.action = "{{ route('egreso.store') }}";
            }
        });
    </script>

</x-app-layout>
