<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Clientes') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">

                <!-- Barra superior -->
                <div class="flex justify-between items-center mb-4">
                    <a href="{{ route('clientes.create') }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                        Nuevo Cliente
                    </a>

                    <!-- Filtro -->
                    <form method="GET" action="{{ route('clientes.index') }}" class="flex">
                        <x-input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Buscar nombre o apellido"
                            class="mr-2 w-64" />
                        <x-button class="bg-gray-600 hover:bg-gray-700 text-white">
                            Buscar
                        </x-button>
                    </form>
                </div>

                <!-- Tabla -->
                <table class="min-w-full border border-gray-200 rounded-md">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">#</th>
                            <th class="px-4 py-2 text-left">Nombre y Apellido</th>
                            <th class="px-4 py-2 text-left">DNI</th>
                            <th class="px-4 py-2 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientes as $index => $cliente)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $loop->iteration + ($clientes->currentPage() - 1) * $clientes->perPage() }}</td>
                            <td class="px-4 py-2 uppercase">
                                {{ $cliente->nombre }} {{ $cliente->apellido }}
                            </td>
                            <td class="px-4 py-2">{{ $cliente->dni }}</td>
                            <td class="px-4 py-2 space-x-2">
                                <!-- Ver -->
                                <a href="{{ route('clientes.show', $cliente->id) }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                    Ver
                                </a>

                                <!-- Editar -->
                                <a href="{{ route('clientes.edit', $cliente->id) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                    Editar
                                </a>

                                <!-- Eliminar (estado → inactivo) -->
                                <form method="POST" action="{{ route('clientes.inactivar', $cliente->id) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"
                                        onclick="return confirm('¿Desea marcar como inactivo este cliente?')">
                                        Inactivar
                                    </button>
                                </form>

                                <!-- Subir archivos -->
                                <a href="{{ route('clientes.archivos', $cliente->id) }}"
                                    class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm">
                                    Subir Archivos
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Paginación -->
                <div class="mt-4">
                    {{ $clientes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>