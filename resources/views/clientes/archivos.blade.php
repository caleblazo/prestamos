<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subir Archivos del Cliente') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">

                <!-- Mensaje de éxito -->
                @if(session('success'))
                <div class="mb-4 text-green-600 font-semibold">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Errores -->
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
                <form method="POST" action="{{ route('clientes.archivos.store', $cliente->id) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <x-label for="dni" value="Archivo DNI" />
                        <x-input id="dni" name="dni" type="file" class="mt-1 block w-full" required />
                    </div>

                    <div class="mb-4">
                        <x-label for="recibo" value="Archivo Recibo" />
                        <x-input id="recibo" name="recibo" type="file" class="mt-1 block w-full" required />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-button class="bg-indigo-600 hover:bg-indigo-700 text-white">
                            Subir Archivos
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>