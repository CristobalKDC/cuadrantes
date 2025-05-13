<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Vista Usuario
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <p>Bienvenido, {{ Auth::user()->name }}. <br> Esta es tu vista personalizada como usuario, aqui podras acceder a tus cuadrantes y ver los horarios asignados.</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-center p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Mis cuadrantes "beta sin ruta"</h2>
            {{-- <a href="{{ route('cuadrantes.usuario') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
                Acceder
            </a> --}}
            <a href="" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
                Acceder
            </a>
        </div>
    </div>

</x-app-layout>
