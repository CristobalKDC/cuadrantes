@php
    // Definir la variable ANTES de cualquier uso en la vista
    $es_jefe = auth()->check() && isset(auth()->user()->es_jefe) && auth()->user()->es_jefe == 1;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cuadrante: {{ $horario->titulo }}
            <br>
            <span class="text-base text-gray-600">
                {{ \Illuminate\Support\Str::ucfirst(\Illuminate\Support\Str::title(\Carbon\Carbon::parse($horario->fecha_inicio)->locale('es')->translatedFormat('l d \d\e F \d\e Y'))) }}
                -
                {{ \Illuminate\Support\Str::ucfirst(\Illuminate\Support\Str::title(\Carbon\Carbon::parse($horario->fecha_fin)->locale('es')->translatedFormat('l d \d\e F \d\e Y'))) }}
            </span>
        </h2>
    </x-slot>

    <div class="py-6" x-data="cuadrante()">
        <div class="w-[80%] mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow rounded" style="overflow-x:auto;">
            @if($es_jefe)
                <a href="{{ route('dashboard') }}" class="inline-block mb-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition text-sm">
                    ← Volver al menú principal.
                </a>
            @endif
            <a href="{{ $es_jefe ? route('cuadrantes.index') : route('cuadrantes.usuario') }}" class="inline-block mb-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition text-sm ml-2">
                ← Volver a todos los cuadrantes
            </a>
            
            @if($es_jefe)
            <button
                id="vaciar-cuadrante-btn"
                class="inline-block mb-4 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition text-sm ml-2"
                type="button"
            >
                Vaciar cuadrante
            </button>
            @endif

            <div class="flex justify-end items-center mb-2 gap-2">
                <button
                    x-show="fechaStart > 0"
                    @click="fechaStart = Math.max(0, fechaStart - maxDias)"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-2 py-1 rounded"
                    title="Ver días anteriores"
                >
                    &#8592;
                </button>
                <button
                    x-show="fechaStart + maxDias < fechas.length"
                    @click="
                        let resto = fechas.length - (fechaStart + maxDias);
                        if (resto > maxDias) {
                            fechaStart = fechaStart + maxDias;
                        } else {
                            fechaStart = fechas.length - resto;
                        }
                    "
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-2 py-1 rounded"
                    title="Ver días siguientes"
                >
                    &#8594;
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="table-auto min-w-max w-full text-center border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border p-2">Nombre</th>
                            <template x-for="(fecha, idx) in fechas.slice(fechaStart, Math.min(fechaStart + maxDias, fechas.length))" :key="fecha">
                                <th class="border p-2" x-text="new Date(fecha).toLocaleDateString('es-ES', { weekday: 'short', day: '2-digit', month: '2-digit' }).replace(/^\w/, c => c.toUpperCase())"></th>
                            </template>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="selectedUser in selectedUsers" :key="selectedUser.id">
                            <tr>
                                <td class="border p-2 font-semibold text-center align-middle">
                                    @if($es_jefe)
                                        <span
                                            x-text="(selectedUser.apodo && selectedUser.apodo.trim() !== '' ? selectedUser.apodo : selectedUser.name) + ' ' + selectedUser.apellidos"
                                            class="cursor-pointer hover:underline text-blue-600"
                                            @click="mostrarAccionesUsuario(selectedUser)"
                                            style="display: inline-block; width: 100%; height: 100%; vertical-align: middle;"
                                        ></span>
                                    @else
                                        <span
                                            x-text="(selectedUser.apodo && selectedUser.apodo.trim() !== '' ? selectedUser.apodo : selectedUser.name) + ' ' + selectedUser.apellidos"
                                            class="text-gray-800"
                                            style="display: inline-block; width: 100%; height: 100%; vertical-align: middle;"
                                        ></span>
                                    @endif
                                </td>
                                <template x-for="(fecha, idx) in fechas.slice(fechaStart, Math.min(fechaStart + maxDias, fechas.length))" :key="fecha">
                                    <td
                                        class="border p-2 relative"
                                        :class="{
                                            'cursor-pointer': seleccionMultipleCrear,
                                            'cursor-default': !seleccionMultipleCrear,
                                            'bg-yellow-100': new Date(fecha) > new Date() && (!seleccionMultipleCrear || !checkedCrear.includes(selectedUser.id + '|' + fecha)),
                                            'bg-red-100': new Date(fecha) < new Date().setHours(0, 0, 0, 0) && (!seleccionMultipleCrear || !checkedCrear.includes(selectedUser.id + '|' + fecha)),
                                            'bg-green-100': new Date(fecha).toDateString() === new Date().toDateString() && (!seleccionMultipleCrear || !checkedCrear.includes(selectedUser.id + '|' + fecha)),
                                            'bg-blue-200': seleccionMultipleCrear && checkedCrear.includes(selectedUser.id + '|' + fecha)
                                        }"
                                        @click="seleccionMultipleCrear ? toggleCrearSeleccion(selectedUser.id + '|' + fecha) : null"
                                    >
                                        <template x-if="selectedUser.entradas && selectedUser.entradas[fecha] && Array.isArray(selectedUser.entradas[fecha]) && selectedUser.entradas[fecha].length">
                                            <div>
                                                <template x-for="(entrada, idx2) in selectedUser.entradas[fecha]" :key="idx2">
                                                    <div class="mb-1 flex items-center justify-center text-center gap-2">
                                                        @if($es_jefe)
                                                        <input
                                                            type="checkbox"
                                                            :id="'check-' + selectedUser.id + '-' + fecha + '-' + idx2"
                                                            x-model="checkedEntradas"
                                                            :value="selectedUser.id + '|' + fecha + '|' + idx2"
                                                            class="mr-2"
                                                        >
                                                        @endif
                                                        <span x-text="'De ' + entrada.hora_inicio.substring(0,5) + ' a ' + entrada.hora_fin.substring(0,5)"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="!selectedUser.entradas || !selectedUser.entradas[fecha] || (Array.isArray(selectedUser.entradas[fecha]) && selectedUser.entradas[fecha].length === 0)">
                                            <div>
                                                <span class="text-gray-400 text-xs" x-show="false">Sin horario</span>
                                            </div>
                                        </template>
                                        @if($es_jefe)
                                        <!-- Icono "+" fijo abajo a la derecha de la celda, más pequeño -->
                                        <button
                                            type="button"
                                            class="absolute bottom-1 right-1 text-indigo-700 hover:text-indigo-900 p-0.5 rounded-full bg-white shadow"
                                            title="Añadir horario"
                                            @click.stop="abrirModalHorario(selectedUser, fecha)"
                                            style="z-index:10;"
                                            x-show="!seleccionMultipleCrear"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                        @endif
                                    </td>
                                </template>
                                
                            </tr>
                        </template>
                        @if($es_jefe)
                        <tr>
                            <td class="border p-2 font-semibold text-center align-middle">
                                <div class="flex justify-center items-center h-full">
                                    <button type="button" class="bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700" @click="openUserModal()">
                                        Añadir usuario
                                    </button>
                                </div>
                            </td>
                            <template x-for="(fecha, idx) in fechas.slice(fechaStart, Math.min(fechaStart + maxDias, fechas.length))" :key="fecha">
                                <td class="border p-2">-</td>
                            </template>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Modal de Añadir Usuario -->
            <div x-show="isUserModalOpen" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-white p-6 rounded shadow-lg w-[50vw] max-w-lg">
                    <h3 class="text-xl font-semibold mb-4">Buscar Usuario</h3>
                    <input type="text" x-model="userSearchQuery" class="w-full p-2 border border-gray-300 rounded mb-4" placeholder="Buscar por nombre, apellidos o DNI">
                    <div class="max-h-60 overflow-y-auto">
                        <table class="w-full table-auto">
                            <thead>
                                <tr>
                                    <th class="border p-2">Nombre</th>
                                    <th class="border p-2">Apellidos</th>
                                    <th class="border p-2">DNI</th>
                                    <th class="border p-2">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="user in filteredUsers" :key="user.id">
                                    <tr>
                                        <td class="border p-2" x-text="user.name"></td>
                                        <td class="border p-2" x-text="user.apellidos"></td>
                                        <td class="border p-2" x-text="user.dni"></td>
                                        <td class="border p-2 text-center">
                                            <button type="button" @click="addUser(user)" class="bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700">
                                                Seleccionar
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button @click="closeUserModal()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cerrar</button>
                    </div>
                </div>
            </div>

            <!-- Modal para agregar/editar horario -->
            <div x-show="isHorarioModalOpen" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-white p-6 rounded shadow-lg w-[40vw] max-w-md">
                    <h3 class="text-xl font-semibold mb-4">Agregar / Editar Horario</h3>
                    <p class="mb-2 font-semibold" x-text="modalUser ? (modalUser.apodo ?? modalUser.name) + ' ' + modalUser.apellidos : ''"></p>
                    <p class="mb-4 font-semibold" x-text="modalFecha ? new Date(modalFecha).toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : ''"></p>
                    
                    <div class="mb-4">
                        <label for="hora_inicio" class="block mb-1 font-medium">Hora inicio:</label>
                        <input type="time" id="hora_inicio" x-model="modalHoraInicio" class="w-full border border-gray-300 rounded p-2">
                    </div>
                    <div class="mb-4">
                        <label for="hora_fin" class="block mb-1 font-medium">Hora fin:</label>
                        <input type="time" id="hora_fin" x-model="modalHoraFin" class="w-full border border-gray-300 rounded p-2">
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button @click="cerrarModalHorario()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cancelar</button>
                        <button @click="guardarHorario()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Guardar</button>
                    </div>
                </div>
            </div>

            <!-- Modal para cambiar usuario -->
            <div x-show="isCambiarUsuarioModalOpen" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-white p-6 rounded shadow-lg w-[50vw] max-w-lg">
                    <h3 class="text-xl font-semibold mb-4">Selecciona un nuevo usuario</h3>
                    <input type="text" x-model="cambiarUserSearchQuery" class="w-full p-2 border border-gray-300 rounded mb-4" placeholder="Buscar por nombre, apellidos o DNI">
                    <div class="max-h-60 overflow-y-auto">
                        <table class="w-full table-auto">
                            <thead>
                                <tr>
                                    <th class="border p-2">Nombre</th>
                                    <th class="border p-2">Apellidos</th>
                                    <th class="border p-2">DNI</th>
                                    <th class="border p-2">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="user in cambiarFilteredUsers" :key="user.id">
                                    <tr>
                                        <td class="border p-2" x-text="user.name"></td>
                                        <td class="border p-2" x-text="user.apellidos"></td>
                                        <td class="border p-2" x-text="user.dni"></td>
                                        <td class="border p-2 text-center">
                                            <button type="button" @click="cambiarUsuarioSeleccionado(user)" class="bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700">
                                                Seleccionar
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button @click="cerrarCambiarUsuario()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cerrar</button>
                    </div>
                </div>
            </div>

            @if($es_jefe)
            <div class="mt-6 flex flex-row justify-between items-center">
                <div class="flex gap-2">
                    <template x-if="checkedEntradas.length > 0">
                        <button
                            type="button"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                            @click="modificarSeleccionados"
                        >
                            Modificar seleccionados
                        </button>
                    </template>
                    <!-- Nuevo botón para crear varios horarios -->
                    <template x-if="true">
                        <button
                            type="button"
                            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
                            @click="activarSeleccionMultipleCrear"
                            x-show="!seleccionMultipleCrear"
                        >
                            Crear varios horarios
                        </button>
                    </template>
                    <!-- Botón para crear los horarios seleccionados -->
                    <template x-if="seleccionMultipleCrear && checkedCrear.length > 0">
                        <button
                            type="button"
                            class="bg-indigo-700 text-white px-4 py-2 rounded hover:bg-indigo-800"
                            @click="crearSeleccionados"
                        >
                            Crear horarios seleccionados
                        </button>
                    </template>
                    <!-- Botón para cancelar selección múltiple -->
                    <template x-if="seleccionMultipleCrear">
                        <button
                            type="button"
                            class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500"
                            @click="cancelarSeleccionMultipleCrear"
                        >
                            Cancelar
                        </button>
                    </template>
                    <template x-if="checkedEntradas.length > 0">
                        <button
                            type="button"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                            @click="eliminarSeleccionados"
                        >
                            Eliminar seleccionados
                        </button>
                    </template>
                </div>
                <form @submit.prevent="guardarEntradas">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Guardar
                    </button>
                </form>
            </div>
            @endif

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('cuadrante', () => ({
                        isUserModalOpen: false,
                        userSearchQuery: '',
                        isHorarioModalOpen: false,
                        modalUser: null,
                        modalFecha: null,
                        modalHoraInicio: '',
                        modalHoraFin: '',
                        modalEntradaIdx: null,
                        isCambiarUsuarioModalOpen: false,
                        cambiarUserSearchQuery: '',
                        cambiarUsuarioIdx: null,
                        users: @json($usuarios),
                        fechas: @json($fechas),
                        maxDias: 7,
                        fechaStart: 0,
                        selectedUsers: (() => {
                            // Normalizar entradas para que siempre sean arrays
                            let usuarios = @json($usuariosConEntrada);
                            usuarios.forEach(user => {
                                if (user.entradas) {
                                    Object.keys(user.entradas).forEach(fecha => {
                                        if (!Array.isArray(user.entradas[fecha])) {
                                            // Si es un objeto simple, conviértelo en array
                                            user.entradas[fecha] = [user.entradas[fecha]];
                                        }
                                    });
                                }
                            });
                            return usuarios;
                        })(),
                        checkedEntradas: [],
                        checkedCrear: [],
                        _modificarSeleccionados: [],
                        _modificarVarios: false,
                        seleccionMultipleCrear: false,
                        cancelarSeleccionMultipleCrear() {
                            this.seleccionMultipleCrear = false;
                            this.checkedCrear = [];
                        },
                        toggleCrearSeleccion(valor) {
                            const idx = this.checkedCrear.indexOf(valor);
                            if (idx === -1) {
                                this.checkedCrear.push(valor);
                            } else {
                                this.checkedCrear.splice(idx, 1);
                            }
                        },
                        init() {
                            // Calcular la posición inicial para mostrar la franja de 7 días que incluye la fecha actual
                            const today = new Date().toISOString().split('T')[0]; // Obtener la fecha actual en formato YYYY-MM-DD
                            const indexToday = this.fechas.findIndex(fecha => fecha === today);
                            if (indexToday !== -1) {
                                this.fechaStart = Math.max(0, indexToday - Math.floor(this.maxDias / 2));
                            }
                        },
                        filteredUsers() {
                            if (this.userSearchQuery === '') return this.users;
                            return this.users.filter(user =>
                                user.name.toLowerCase().includes(this.userSearchQuery.toLowerCase()) ||
                                user.apellidos.toLowerCase().includes(this.userSearchQuery.toLowerCase()) ||
                                user.dni.toLowerCase().includes(this.userSearchQuery.toLowerCase())
                            );
                        },
                        cambiarFilteredUsers() {
                            // Excluir al usuario seleccionado (cambiarUsuarioIdx) de la lista de usuarios filtrados
                            return this.users.filter(user =>
                                (user.name.toLowerCase().includes(this.cambiarUserSearchQuery.toLowerCase()) ||
                                user.apellidos.toLowerCase().includes(this.cambiarUserSearchQuery.toLowerCase()) ||
                                user.dni.toLowerCase().includes(this.cambiarUserSearchQuery.toLowerCase())) &&
                                (!this.selectedUsers[this.cambiarUsuarioIdx] || user.id !== this.selectedUsers[this.cambiarUsuarioIdx].id)
                            );
                        },
                        openUserModal() {
                            this.isUserModalOpen = true;
                        },
                        closeUserModal() {
                            this.isUserModalOpen = false;
                        },
                        addUser(user) {
                            // Evitar agregar duplicados
                            if (!this.selectedUsers.find(u => u.id === user.id)) {
                                user.entradas = {}; // inicializar entradas vacío
                                this.selectedUsers.push(user);
                            }
                            this.closeUserModal();

                            // Guardar automáticamente el usuario sin horarios (campos null)
                            let payload = [{
                                user_id: user.id,
                                fecha: null,
                                hora_inicio: null,
                                hora_fin: null,
                            }];

                            fetch(`{{ route('horarios.entradas.guardar', ['horario' => $horario->id]) }}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    entradas: payload
                                })
                            })
                            .then(response => {
                                const contentType = response.headers.get('content-type');
                                if (!response.ok) throw new Error('Error al guardar');
                                if (contentType && contentType.indexOf('application/json') !== -1) {
                                    return response.json();
                                } else {
                                    throw new Error('Respuesta inesperada del servidor');
                                }
                            })
                            .then(data => {
                                // Opcional: mostrar mensaje o recargar usuarios si quieres feedback inmediato
                                // Swal.fire({ icon: 'success', title: 'Usuario añadido', text: 'Usuario añadido correctamente' });
                            })
                            .catch(error => {
                                console.error(error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Hubo un error al añadir el usuario. Por favor, revisa los datos o inténtalo de nuevo.'
                                });
                            });
                        },
                        crearSeleccionados() {
                            if (!this.checkedCrear.length) return;
                            // Guardar los seleccionados para crear después
                            this._crearSeleccionados = this.checkedCrear.map(val => {
                                const [userId, fecha] = val.split('|');
                                return { userId, fecha };
                            });
                            // Abrir el modal para el primero seleccionado
                            const [userId, fecha] = this.checkedCrear[0].split('|');
                            const user = this.selectedUsers.find(u => u.id == userId);
                            if (user && fecha) {
                                this.abrirModalHorario(user, fecha, null, false, true);
                            }
                            // Al terminar, desactivar selección múltiple (se desactiva tras guardar en guardarHorario)
                        },
                        abrirModalHorario(user, fecha, idx = null, modificarVarios = false, crearVarios = false) {
                            this.modalUser = user;
                            this.modalFecha = fecha;
                            this.modalEntradaIdx = idx;
                            this._modificarVarios = modificarVarios || false;
                            this._crearVarios = crearVarios || false;

                            let minHoraInicio = '';
                            if (
                                idx === null &&
                                user.entradas &&
                                user.entradas[fecha] &&
                                Array.isArray(user.entradas[fecha]) &&
                                user.entradas[fecha].length
                            ) {
                                let maxHoraFin = user.entradas[fecha]
                                    .map(e => e.hora_fin)
                                    .sort()
                                    .pop();
                                minHoraInicio = maxHoraFin ? maxHoraFin.substring(0,5) : '';
                            }

                            if (idx !== null && user.entradas && user.entradas[fecha] && Array.isArray(user.entradas[fecha]) && user.entradas[fecha][idx]) {
                                this.modalHoraInicio = user.entradas[fecha][idx].hora_inicio;
                                this.modalHoraFin = user.entradas[fecha][idx].hora_fin;
                            } else {
                                this.modalHoraInicio = '';
                                this.modalHoraFin = '';
                            }

                            this.isHorarioModalOpen = true;
                            this.minHoraInicio = minHoraInicio;
                        },
                        cerrarModalHorario() {
                            this.isHorarioModalOpen = false;
                            this.modalUser = null;
                            this.modalFecha = null;
                            this.modalHoraInicio = '';
                            this.modalHoraFin = '';
                            this.modalEntradaIdx = null;
                        },
                        guardarHorario() {
                            // Validación de campos vacíos
                            if (!this.modalHoraInicio || !this.modalHoraFin) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Campos requeridos',
                                    text: 'Por favor, completa hora inicio y hora fin'
                                });
                                return;
                            }

                            // Validar hora_inicio respecto al horario anterior real (según el orden de los horarios)
                            if (this.modalUser && this.modalFecha && this.modalUser.entradas && this.modalUser.entradas[this.modalFecha]) {
                                let entradas = this.modalUser.entradas[this.modalFecha];
                                // Ordenar por hora_inicio
                                let entradasOrdenadas = entradas.slice().sort((a, b) => a.hora_inicio.localeCompare(b.hora_inicio));

                                // Si estamos modificando varios, buscar el menor índice seleccionado
                                if (this._modificarVarios && Array.isArray(this._modificarSeleccionados) && this._modificarSeleccionados.length > 0) {
                                    // Obtener los índices de los seleccionados en el array ordenado
                                    let indicesSeleccionados = this._modificarSeleccionados.map(sel => {
                                        const entrada = entradas[sel.idx];
                                        return entradasOrdenadas.findIndex(e =>
                                            e.hora_inicio === entrada.hora_inicio && e.hora_fin === entrada.hora_fin
                                        );
                                    }).sort((a, b) => a - b);

                                    // Si no se seleccionan todos, comprobar el anterior al primero seleccionado
                                    if (indicesSeleccionados.length < entradasOrdenadas.length && indicesSeleccionados[0] > 0) {
                                        let anterior = entradasOrdenadas[indicesSeleccionados[0] - 1];
                                        if (anterior && anterior.hora_fin && this.modalHoraInicio <= anterior.hora_fin) {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Hora inválida',
                                                text: 'La hora de inicio debe ser posterior a la hora de salida del horario anterior de ese día: ' + anterior.hora_fin
                                            });
                                            return;
                                        }
                                    }
                                } else {
                                    // Modificación individual o creación
                                    let idxEnOrden = entradasOrdenadas.length;
                                    if (this.modalEntradaIdx !== null) {
                                        // Buscar el índice real del horario que se está editando en el array ordenado
                                        const actual = entradas[this.modalEntradaIdx];
                                        idxEnOrden = entradasOrdenadas.findIndex(e =>
                                            e.hora_inicio === actual.hora_inicio && e.hora_fin === actual.hora_fin
                                        );
                                    }
                                    if (idxEnOrden > 0) {
                                        let anterior = entradasOrdenadas[idxEnOrden - 1];
                                        if (anterior && anterior.hora_fin && this.modalHoraInicio <= anterior.hora_fin) {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Hora inválida',
                                                text: 'La hora de inicio debe ser posterior a la hora de salida del horario anterior de ese día: ' + anterior.hora_fin
                                            });
                                            return;
                                        }
                                    }
                                }
                            }

                            // Validar hora_inicio respecto a la hora_fin más tardía de las casillas seleccionadas
                            if (this._crearVarios && Array.isArray(this._crearSeleccionados) && this._crearSeleccionados.length > 0) {
                                let maxHoraFin = null;

                                this._crearSeleccionados.forEach(sel => {
                                    const user = this.selectedUsers.find(u => u.id == sel.userId);
                                    if (user && user.entradas && user.entradas[sel.fecha]) {
                                        const entradas = user.entradas[sel.fecha];
                                        entradas.forEach(entrada => {
                                            if (entrada.hora_fin && (!maxHoraFin || entrada.hora_fin > maxHoraFin)) {
                                                maxHoraFin = entrada.hora_fin;
                                            }
                                        });
                                    }
                                });

                                if (maxHoraFin && this.modalHoraInicio <= maxHoraFin) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Hora inválida',
                                        text: `La hora de inicio debe ser posterior a la hora de salida más tardía: ${maxHoraFin}`
                                    });
                                    return;
                                }
                            }

                            if (this._modificarVarios && Array.isArray(this._modificarSeleccionados) && this._modificarSeleccionados.length > 0) {
                                // Modificar todos los seleccionados
                                this._modificarSeleccionados.forEach(sel => {
                                    const user = this.selectedUsers.find(u => u.id == sel.userId);
                                    if (user && user.entradas && user.entradas[sel.fecha] && user.entradas[sel.fecha][sel.idx]) {
                                        user.entradas[sel.fecha][sel.idx].hora_inicio = this.modalHoraInicio;
                                        user.entradas[sel.fecha][sel.idx].hora_fin = this.modalHoraFin;
                                    }
                                });
                                this.checkedEntradas = [];
                                this._modificarSeleccionados = [];
                                this._modificarVarios = false;
                            } else if (this._crearVarios && Array.isArray(this._crearSeleccionados) && this._crearSeleccionados.length > 0) {
                                // Crear todos los seleccionados
                                this._crearSeleccionados.forEach(sel => {
                                    const user = this.selectedUsers.find(u => u.id == sel.userId);
                                    if (!user.entradas) user.entradas = {};
                                    if (!user.entradas[sel.fecha] || !Array.isArray(user.entradas[sel.fecha])) {
                                        user.entradas[sel.fecha] = [];
                                    }
                                    user.entradas[sel.fecha].push({
                                        hora_inicio: this.modalHoraInicio,
                                        hora_fin: this.modalHoraFin
                                    });
                                });
                                this.checkedCrear = [];
                                this._crearSeleccionados = [];
                                this._crearVarios = false;
                            } else {
                                if (!this.modalUser.entradas) this.modalUser.entradas = {};
                                if (!this.modalUser.entradas[this.modalFecha] || !Array.isArray(this.modalUser.entradas[this.modalFecha])) {
                                    this.modalUser.entradas[this.modalFecha] = [];
                                }
                                if (this.modalEntradaIdx !== null) {
                                    // Editar horario existente
                                    this.modalUser.entradas[this.modalFecha][this.modalEntradaIdx] = {
                                        hora_inicio: this.modalHoraInicio,
                                        hora_fin: this.modalHoraFin
                                    };
                                } else {
                                    // Añadir nuevo horario
                                    this.modalUser.entradas[this.modalFecha].push({
                                        hora_inicio: this.modalHoraInicio,
                                        hora_fin: this.modalHoraFin
                                    });
                                }
                            }
                            this.cerrarModalHorario();
                        },
                        guardarEntradas() {
                            let payload = [];

                            // Guardar horarios existentes seleccionados
                            this.selectedUsers.forEach(user => {
                                let tieneHorarios = false;
                                if (user.entradas && Object.keys(user.entradas).length > 0) {
                                    Object.entries(user.entradas).forEach(([fecha, entradas]) => {
                                        if (Array.isArray(entradas) && entradas.length > 0) {
                                            entradas.forEach(entrada => {
                                                if (entrada && entrada.hora_inicio && entrada.hora_fin) {
                                                    tieneHorarios = true;
                                                    payload.push({
                                                        user_id: user.id,
                                                        fecha,
                                                        hora_inicio: entrada.hora_inicio,
                                                        hora_fin: entrada.hora_fin,
                                                    });
                                                }
                                            });
                                        }
                                    });
                                }
                                // Si no tiene ningún horario, enviar igualmente el usuario con campos nulos
                                if (!tieneHorarios) {
                                    payload.push({
                                        user_id: user.id,
                                        fecha: null,
                                        hora_inicio: null,
                                        hora_fin: null,
                                    });
                                }
                            });

                            // Crear nuevos horarios para los checks de crear
                            if (this.checkedCrear && this.checkedCrear.length > 0) {
                                this.checkedCrear.forEach(val => {
                                    const [userId, fecha] = val.split('|');
                                    payload.push({
                                        user_id: userId,
                                        fecha: fecha,
                                        hora_inicio: '09:00', // valor por defecto, puedes cambiarlo
                                        hora_fin: '17:00',    // valor por defecto, puedes cambiarlo
                                    });
                                });
                                this.checkedCrear = [];
                            }

                            fetch(`{{ route('horarios.entradas.guardar', ['horario' => $horario->id]) }}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    entradas: payload
                                })
                            })
                            .then(response => {
                                const contentType = response.headers.get('content-type');
                                if (!response.ok) throw new Error('Error al guardar');
                                if (contentType && contentType.indexOf('application/json') !== -1) {
                                    return response.json();
                                } else {
                                    throw new Error('Respuesta inesperada del servidor');
                                }
                            })
                            .then(data => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Guardado',
                                    text: 'Horarios guardados correctamente'
                                }).then(() => {
                                    window.location.reload();
                                });
                            })
                            .catch(error => {
                                console.error(error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Hubo un error al guardar los horarios. Por favor, revisa los datos o inténtalo de nuevo.'
                                });
                            });
                        },
                        eliminarUsuario(userId) {
                            this.selectedUsers = this.selectedUsers.filter(u => u.id !== userId);
                        },
                        eliminarHorario(user, fecha, idx) {
                            if (user.entradas && user.entradas[fecha] && Array.isArray(user.entradas[fecha])) {
                                user.entradas[fecha].splice(idx, 1);
                            }
                        },
                        abrirCambiarUsuario(selectedUser) {
                            this.cambiarUsuarioIdx = this.selectedUsers.findIndex(u => u.id === selectedUser.id);
                            this.cambiarUserSearchQuery = '';
                            this.isCambiarUsuarioModalOpen = true;
                        },
                        cerrarCambiarUsuario() {
                            this.isCambiarUsuarioModalOpen = false;
                            this.cambiarUsuarioIdx = null;
                        },
                        cambiarUsuarioSeleccionado(user) {
                            if (this.cambiarUsuarioIdx !== null) {
                                const currentUser = this.selectedUsers[this.cambiarUsuarioIdx];

                                // Verificar si el usuario ya está en el cuadrante
                                const existingUserIdx = this.selectedUsers.findIndex(u => u.id === user.id);

                                if (existingUserIdx !== -1) {
                                    // Intercambiar posiciones entre el usuario actual y el seleccionado
                                    this.selectedUsers[existingUserIdx] = currentUser;
                                }

                                // Reemplazar el usuario actual con el seleccionado
                                this.selectedUsers[this.cambiarUsuarioIdx] = {
                                    ...user,
                                    entradas: currentUser.entradas // Mantener los horarios del usuario actual
                                };

                                // Enviar solicitud al servidor para actualizar los user_id
                                fetch(`{{ route('horarios.cambiarUsuario', ['horario' => $horario->id]) }}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        antiguo_user_id: currentUser.id,
                                        nuevo_user_id: user.id
                                    })
                                })
                                .then(response => {
                                    if (!response.ok) throw new Error('Error al actualizar los horarios');
                                    return response.json();
                                })
                                .then(data => {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Usuario cambiado',
                                        text: 'Los horarios se han actualizado correctamente.'
                                    }).then(() => {
                                        window.location.reload(); // Recargar la página después de aceptar el mensaje
                                    });
                                })
                                .catch(error => {
                                    console.error(error);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Hubo un problema al actualizar los horarios. Por favor, inténtalo de nuevo.'
                                    });
                                });
                            }

                            this.cerrarCambiarUsuario();
                        },
                        mostrarAccionesUsuario(selectedUser) {
                            Swal.fire({
                                title: (selectedUser.apodo && selectedUser.apodo.trim() !== '' ? selectedUser.apodo : selectedUser.name) + ' ' + selectedUser.apellidos,
                                showCancelButton: true,
                                showDenyButton: true,
                                confirmButtonText: 'Cambiar usuario',
                                denyButtonText: 'Eliminar',
                                cancelButtonText: 'Cancelar',
                                icon: 'info'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    this.abrirCambiarUsuario(selectedUser);
                                } else if (result.isDenied) {
                                    this.eliminarUsuario(selectedUser.id);
                                }
                            });
                        },
                        eliminarSeleccionados() {
                            this.checkedEntradas.forEach(val => {
                                const [userId, fecha, idx] = val.split('|');
                                const user = this.selectedUsers.find(u => u.id == userId);
                                if (user && user.entradas && user.entradas[fecha] && Array.isArray(user.entradas[fecha])) {
                                    user.entradas[fecha].splice(idx, 1);
                                }
                            });
                            this.checkedEntradas = [];
                        },
                        modificarSeleccionados() {
                            if (this.checkedEntradas.length === 0) return;
                            // Guardar los seleccionados para modificar después
                            this._modificarSeleccionados = this.checkedEntradas.map(val => {
                                const [userId, fecha, idx] = val.split('|');
                                return { userId, fecha, idx: Number(idx) };
                            });
                            // Tomar el primero para abrir el modal con sus datos
                            const sel = this._modificarSeleccionados[0];
                            const user = this.selectedUsers.find(u => u.id == sel.userId);
                            if (!user || !user.entradas || !user.entradas[sel.fecha] || !user.entradas[sel.fecha][sel.idx]) return;
                            this.abrirModalHorario(user, sel.fecha, sel.idx, true);
                        },
                        activarSeleccionMultipleCrear() {
                            this.seleccionMultipleCrear = true;
                            this.checkedCrear = [];
                        },
                    }));
                });

                // Vaciar cuadrante
                document.addEventListener('DOMContentLoaded', function () {
                    const btn = document.getElementById('vaciar-cuadrante-btn');
                    if (btn) {
                        btn.addEventListener('click', function () {
                            Swal.fire({
                                title: '¿Vaciar cuadrante?',
                                text: 'Se eliminarán todos los horarios y usuarios de este cuadrante. Esta acción no se puede deshacer.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: 'Sí, vaciar',
                                cancelButtonText: 'Cancelar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    fetch("{{ route('horarios.vaciar', ['horario' => $horario->id]) }}", {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(response => {
                                        if (!response.ok) throw new Error('Error al vaciar cuadrante');
                                        return response.json();
                                    })
                                    .then(data => {
                                        Swal.fire('¡Vaciado!', 'El cuadrante ha sido vaciado.', 'success')
                                            .then(() => window.location.reload());
                                    })
                                    .catch(() => {
                                        Swal.fire('Error', 'No se pudo vaciar el cuadrante.', 'error');
                                    });
                                }
                            });
                        });
                    }
                });
            </script>
        </div>
    </div>
</x-app-layout>
