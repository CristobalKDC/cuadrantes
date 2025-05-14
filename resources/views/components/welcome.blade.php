<div class="p-6 lg:p-8 bg-white border-b border-gray-200">

    <h1 class="mt-8 text-2xl font-medium text-gray-900">
        Crea tus propios cuadrantes
    </h1>

    <p class="mt-6 text-gray-500 leading-relaxed">
        Puedes crear cuadrantes seleccionando la fecha de inicio y fin para el cuadrante.<br>
        Luego agrega los usuarios al cuadrante y administra los horarios según el día, puedes agregar varios horarios en el mismo día.
    </p>
</div>

<div class="bg-gray-200 bg-opacity-25 p-6 lg:p-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">

        <!-- Crear Cuadrante -->
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Crear Cuadrante</h2>
            <p class="text-gray-600 text-sm mb-4">
                Selecciona el rango de fechas, agrega usuarios y define horarios para cada día.
            </p>
            <a href="{{ route('cuadrantes.create') }}" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                Crear
            </a>
        </div>

        <!-- Modificar Cuadrante -->
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Modificar Cuadrante</h2>
            <p class="text-gray-600 text-sm mb-4">
                Edita cuadrantes existentes para cambiar usuarios, horarios o fechas.
            </p>
            <a href="" class="inline-block bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
                Modificar
            </a>
        </div>

        <!-- Listar Cuadrantes -->
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Listar Cuadrantes</h2>
            <p class="text-gray-600 text-sm mb-4">
                Consulta todos los cuadrantes disponibles filtrando por mes o usuario.
            </p>
            <a href="{{ route('cuadrantes.index') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Ver Cuadrantes
            </a>
        </div>

    </div>
</div>



    

   

    
</div>
