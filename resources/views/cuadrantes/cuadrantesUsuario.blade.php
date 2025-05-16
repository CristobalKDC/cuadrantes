<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Cuadrantes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <a href="{{ route('vista.usuario') }}" class="inline-block mb-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition text-sm">
                    ← Volver
                </a>
                @if($cuadrantes->isEmpty())
                    <p class="text-gray-600">No estás asignado a ningún cuadrante.</p>
                @else
                    <table class="table-auto w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Título</th>
                                <th class="px-4 py-2">Fecha</th>
                                <th class="px-4 py-2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cuadrantes as $cuadrante)
                                <tr>
                                    <td class="border px-4 py-2">{{ $cuadrante->titulo }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        De {{ \Carbon\Carbon::parse($cuadrante->fecha_inicio)->format('d/m/Y') }} 
                                        a {{ \Carbon\Carbon::parse($cuadrante->fecha_fin)->format('d/m/Y') }}
                                    </td>
                                    <td class="border px-4 py-2 text-center">
                                        <a href="{{ route('horarios.show', $cuadrante->id) }}" class="text-indigo-600 hover:underline">Ver</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>