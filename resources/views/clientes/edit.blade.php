<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Actualizar Cliente') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">

                <!-- Mensaje de éxito -->
                @if(session('success'))
                <div class="mb-4 text-green-600 font-semibold">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Errores de validación -->
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
                <form method="POST" action="{{ route('clientes.update', $cliente->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <x-label for="nombre" value="Nombre" />
                            <x-input id="nombre" name="nombre" type="text" class="mt-1 block w-full"
                                value="{{ old('nombre', $cliente->nombre) }}" required />
                        </div>

                        <!-- Apellido -->
                        <div>
                            <x-label for="apellido" value="Apellido" />
                            <x-input id="apellido" name="apellido" type="text" class="mt-1 block w-full"
                                value="{{ old('apellido', $cliente->apellido) }}" required />
                        </div>

                        <!-- Fecha nacimiento -->
                        <div>
                            <x-label for="fecha_nacimiento" value="Fecha de Nacimiento" />
                            <x-input id="fecha_nacimiento" name="fecha_nacimiento" type="date" class="mt-1 block w-full"
                                value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento) }}" required />
                        </div>

                        <!-- Sexo -->
                        <div>
                            <x-label for="sexo" value="Sexo" />
                            <select id="sexo" name="sexo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="M" {{ old('sexo', $cliente->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('sexo', $cliente->sexo) == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                        </div>

                        <!-- DNI -->
                        <div>
                            <x-label for="dni" value="DNI" />
                            <x-input id="dni" name="dni" type="text" class="mt-1 block w-full bg-gray-100"
                                value="{{ $cliente->dni }}" readonly />
                        </div>

                        <!-- Celular -->
                        <div>
                            <x-label for="celular" value="Celular" />
                            <x-input id="celular" name="celular" type="text" maxlength="9" class="mt-1 block w-full"
                                value="{{ old('celular', $cliente->celular) }}" required />
                        </div>

                        <!-- Correo -->
                        <div>
                            <x-label for="correo" value="Correo" />
                            <x-input id="correo" name="correo" type="email" class="mt-1 block w-full"
                                value="{{ old('correo', $cliente->correo) }}" required />
                        </div>

                        <!-- Estado -->
                        <div>
                            <x-label for="estado" value="Estado" />
                            <select id="estado" name="estado" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="activo" {{ old('estado', $cliente->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado', $cliente->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <!-- Departamento -->
                        <div>
                            <x-label for="departamento_id" value="Departamento" />
                            <select id="departamento_id" name="departamento_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @foreach($departamentos as $departamento)
                                <option value="{{ $departamento->id }}"
                                    {{ old('departamento_id', $cliente->departamento_id) == $departamento->id ? 'selected' : '' }}>
                                    {{ $departamento->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Provincia -->
                        <div>
                            <x-label for="provincia_id" value="Provincia" />
                            <select id="provincia_id" name="provincia_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @foreach($provincias as $provincia)
                                <option value="{{ $provincia->id }}"
                                    {{ old('provincia_id', $cliente->provincia_id) == $provincia->id ? 'selected' : '' }}>
                                    {{ $provincia->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Distrito -->
                        <div>
                            <x-label for="distrito_id" value="Distrito" />
                            <select id="distrito_id" name="distrito_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @foreach($distritos as $distrito)
                                <option value="{{ $distrito->id }}"
                                    {{ old('distrito_id', $cliente->distrito_id) == $distrito->id ? 'selected' : '' }}>
                                    {{ $distrito->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dirección -->
                        <div class="md:col-span-2">
                            <x-label for="direccion" value="Dirección" />
                            <x-input id="direccion" name="direccion" type="text" class="mt-1 block w-full"
                                value="{{ old('direccion', $cliente->direccion) }}" required />
                        </div>

                        <!-- Referencia -->
                        <div class="md:col-span-2">
                            <x-label for="referencia" value="Referencia" />
                            <x-input id="referencia" name="referencia" type="text" class="mt-1 block w-full"
                                value="{{ old('referencia', $cliente->referencia) }}" />
                        </div>

                        <!-- Referente Nombre -->
                        <div>
                            <x-label for="referente_nombre" value="Nombre del Referente" />
                            <x-input id="referente_nombre" name="referente_nombre" type="text" class="mt-1 block w-full"
                                value="{{ old('referente_nombre', $cliente->referente->nombre ?? '') }}" required />
                        </div>

                        <!-- Referente Celular -->
                        <div>
                            <x-label for="referente_celular" value="Celular del Referente" />
                            <x-input id="referente_celular" name="referente_celular" type="text" maxlength="9" class="mt-1 block w-full"
                                value="{{ old('referente_celular', $cliente->referente->celular ?? '') }}" required />
                        </div>
                    </div>

                    <!-- Botón -->
                    <div class="mt-6 flex justify-end">
                        <x-button class="bg-indigo-600 hover:bg-indigo-700 text-white">
                            Actualizar Cliente
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>