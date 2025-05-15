{{-- Usa el layout correcto para Jetstream/Laravel Breeze --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modificar Cuadrantes
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-8 bg-white p-6 rounded shadow">
        <table class="min-w-full table-auto border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">Título</th>
                    <th class="border px-4 py-2">Fecha inicio</th>
                    <th class="border px-4 py-2">Fecha fin</th>
                    <th class="border px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cuadrantes as $cuadrante)
                    <tr>
                        <td class="border px-4 py-2">{{ $cuadrante->titulo }}</td>
                        <td class="border px-4 py-2">{{ $cuadrante->fecha_inicio }}</td>
                        <td class="border px-4 py-2">{{ $cuadrante->fecha_fin }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('cuadrantes.edit', $cuadrante->id) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">Editar</a>
                            <!-- Puedes añadir más acciones aquí -->
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">No hay cuadrantes disponibles.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Volver al menú principal</a>
        </div>
    </div>
</x-app-layout>
