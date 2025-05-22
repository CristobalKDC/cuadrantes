<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Cuadrante
        </h2>
    </x-slot>

    <div class="py-12" x-data="calendarRange" x-init="init()">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 flex justify-center">
           
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10 text-center ">
                <a href="{{ route('dashboard') }}" class="inline-block mb-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition text-sm">
                ← Volver
            </a>
                <h3 class="text-2xl font-bold mb-6">Selecciona el rango de fechas</h3>

                <!-- Calendario directamente visible -->
                <div id="calendar" class="mx-auto max-w-md "></div>

                <!-- Mostrar resultado -->
                <div class="mt-6 text-lg text-gray-700" x-show="inicio && fin">
                    <p><strong>Inicio:</strong> <span x-text="inicio"></span> - <strong>Fin:</strong> <span x-text="fin"></span></p>
                </div>

                <!-- Formulario -->
                <form method="POST" action="{{ route('cuadrantes.store') }}" class="mt-8">
                    @csrf

                    <!-- Titulo -->
                    <div class="mb-4">
                        <label for="titulo" class="block text-left text-lg font-medium text-gray-700">Título</label>
                        <input type="text" id="titulo" name="titulo" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" placeholder="Ingresa el título del cuadrante">
                        <p class="text-sm text-gray-500 mt-1">Dejalo vacio y genera un titulo automatico con las fechas.</p>
                    </div>

                    <input type="hidden" name="fecha_inicio" :value="inicio">
                    <input type="hidden" name="fecha_fin" :value="fin">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition"
                        :disabled="!inicio || !fin">
                        Crear y pasar al cuadrante
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('calendarRange', () => ({
                rango: '',
                inicio: '',
                fin: '',
                init() {
                    flatpickr("#calendar", {
                        mode: "range",
                        inline: true,
                        dateFormat: "Y-m-d",
                        locale: {
                            ...flatpickr.l10ns.default,
                            ...flatpickr.l10ns.es
                        },
                        onChange: (selectedDates) => {
                            const [start, end] = selectedDates.map(date => this.formatDate(date));
                            this.inicio = start || '';
                            this.fin = end || '';
                            this.rango = `${this.inicio} - ${this.fin}`;
                        }
                    });
                },
                formatDate(date) {
                    if (!date) return '';
                    return date.toISOString().split('T')[0];
                }
            }));
        });
    </script>



</x-app-layout>
