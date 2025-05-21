<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista de Cuadrantes
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow rounded">
            <h3 class="text-lg font-semibold mb-4">Cuadrantes creados</h3>

            @if($cuadrantes->isEmpty())
                <p class="text-gray-500">No hay cuadrantes registrados.</p>
            @else
                <table class="table-auto w-full text-left border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border p-2">Título</th>
                            <th class="border p-2">Fecha del cuadrante</th>
                            <th class="border p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cuadrantes as $cuadrante)
                            <tr>
                                <td class="border p-2">{{ $cuadrante->titulo }}</td>
                                <td class="border p-2">De {{ $cuadrante->fecha_inicio }} a {{ $cuadrante->fecha_fin }}</td>
                                <td class="border p-2 flex items-center gap-2">
                                    <a href="{{ route('horarios.show', $cuadrante->id) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">
                                        Modificar horarios
                                    </a>

                                    <form action="{{ route('cuadrantes.destroy', $cuadrante->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este cuadrante?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            
            <a href="{{ route('dashboard') }}" class="inline-block mt-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition text-sm">
                ← Volver
            </a>
        </div>
    </div>
</x-app-layout>
