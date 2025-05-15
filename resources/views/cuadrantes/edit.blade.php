<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar cuadrante
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto mt-8 bg-white p-6 rounded shadow">
        @php
            $defaultTitle = 'Cuadrante del ' . $cuadrante->fecha_inicio . ' al ' . $cuadrante->fecha_fin;
            $isDefaultTitle = trim($cuadrante->titulo) === $defaultTitle;
        @endphp
        <form method="POST" action="{{ route('cuadrantes.update', $cuadrante->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-medium mb-1" for="titulo">Título</label>
                <input
                    type="text"
                    name="titulo"
                    id="titulo"
                    value="{{ old('titulo', $isDefaultTitle ? '' : $cuadrante->titulo) }}"
                    class="w-full border border-gray-300 rounded p-2"
                    placeholder="{{ $defaultTitle }}"
                >
                <p class="text-gray-500 text-xs mt-1">
                    Si no añades un título, se generará automáticamente con las fechas seleccionadas.
                </p>
                @error('titulo')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1" for="fecha_inicio">Fecha de inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio', $cuadrante->fecha_inicio) }}" class="w-full border border-gray-300 rounded p-2" required>
                @error('fecha_inicio')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1" for="fecha_fin">Fecha de fin</label>
                <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin', $cuadrante->fecha_fin) }}" class="w-full border border-gray-300 rounded p-2" required>
                @error('fecha_fin')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex justify-between">
                <a href="{{ route('cuadrantes.modificar') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancelar</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Guardar cambios</button>
            </div>
        </form>
    </div>
</x-app-layout>
