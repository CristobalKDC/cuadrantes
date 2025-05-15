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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow rounded">
            <a href="{{ route('cuadrantes.index') }}" class="inline-block mb-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition text-sm ml-2">
                ← Volver a todos los cuadrantes
            </a>
            <a href="{{ route('dashboard') }}" class="inline-block mb-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition text-sm">
                ← Volver al menú principal.
            </a>
            <button
                id="vaciar-cuadrante-btn"
                class="inline-block mb-4 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition text-sm ml-2"
                type="button"
            >
                Vaciar cuadrante
            </button>

            <div class="overflow-x-auto">
                <table class="table-auto min-w-max w-full text-center border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border p-2">Nombre</th>
                            @foreach($fechas as $fecha)
                                <th class="border p-2">
                                    {{ \Illuminate\Support\Str::ucfirst(\Carbon\Carbon::parse($fecha)->locale('es')->translatedFormat('l d/m')) }}
                                </th>
                            @endforeach
                            <th class="border p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                        <template x-for="selectedUser in selectedUsers" :key="selectedUser.id">
                            <tr>
                                <td class="border p-2 font-semibold text-left">
                                    <div>
                                        <span x-text="(selectedUser.apodo ?? selectedUser.name) + ' ' + selectedUser.apellidos"></span>
                                        
                                    </div>
                                </td>

                                <template x-for="fecha in fechas" :key="fecha">
                                    <td class="border p-2">
                                        <template x-if="selectedUser.entradas && selectedUser.entradas[fecha] && Array.isArray(selectedUser.entradas[fecha]) && selectedUser.entradas[fecha].length">
                                            <div>
                                                <template x-for="(entrada, idx) in selectedUser.entradas[fecha]" :key="idx">
                                                    <div class="mb-1 flex items-center justify-between">
                                                        <span x-text="entrada.hora_inicio.substring(0,5) + ' a ' + entrada.hora_fin.substring(0,5)"></span>
                                                        <div>
                                                            <button 
                                                                class="ml-2 text-xs text-blue-600 hover:underline"
                                                                @click="abrirModalHorario(selectedUser, fecha, idx)">
                                                                Editar
                                                            </button>
                                                            <button
                                                                class="ml-2 text-xs text-red-600 hover:underline"
                                                                @click="eliminarHorario(selectedUser, fecha, idx)">
                                                                Eliminar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="!selectedUser.entradas || !selectedUser.entradas[fecha] || (Array.isArray(selectedUser.entradas[fecha]) && selectedUser.entradas[fecha].length === 0)">
                                            <div>
                                                <span class="text-gray-400 text-xs">Sin horario</span>
                                            </div>
                                        </template>
                                        <button
                                            class="bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700 text-sm mt-1"
                                            @click="abrirModalHorario(selectedUser, fecha)">
                                            +
                                        </button>
                                    </td>
                                </template>
                                <td class="border p-2">
                                    <div class="mt-1">
                                        <button
                                            type="button"
                                            class="bg-yellow-400 text-gray-800 px-2 py-1 rounded text-xs hover:bg-yellow-500"
                                            @click="abrirCambiarUsuario(selectedUser)"
                                            title="Cambiar usuario"
                                        >
                                            Cambiar usuario
                                        </button>
                                    </div>
                                    <button
                                        type="button"
                                        class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 text-xs"
                                        @click="eliminarUsuario(selectedUser.id)"
                                    >
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr>
                            <td class="border p-2 font-semibold text-left">
                                <button type="button" class="bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700" @click="openUserModal()">
                                    Añadir usuario
                                </button>
                            </td>
                            @foreach($fechas as $fecha)
                                <td class="border p-2">-</td>
                            @endforeach
                        </tr>
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

            <div class="mt-6 text-right">
                <form @submit.prevent="guardarEntradas">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Guardar
                    </button>
                </form>
            </div>
        </div>
    </div>

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
                filteredUsers() {
                    if (this.userSearchQuery === '') return this.users;
                    return this.users.filter(user =>
                        user.name.toLowerCase().includes(this.userSearchQuery.toLowerCase()) ||
                        user.apellidos.toLowerCase().includes(this.userSearchQuery.toLowerCase()) ||
                        user.dni.toLowerCase().includes(this.userSearchQuery.toLowerCase())
                    );
                },
                cambiarFilteredUsers() {
                    // Excluir usuarios ya seleccionados excepto el actual
                    return this.users.filter(user =>
                        (user.name.toLowerCase().includes(this.cambiarUserSearchQuery.toLowerCase()) ||
                        user.apellidos.toLowerCase().includes(this.cambiarUserSearchQuery.toLowerCase()) ||
                        user.dni.toLowerCase().includes(this.cambiarUserSearchQuery.toLowerCase()))
                        && !this.selectedUsers.some((u, idx) => u.id === user.id && idx !== this.cambiarUsuarioIdx)
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
                },
                abrirModalHorario(user, fecha, idx = null) {
                    this.modalUser = user;
                    this.modalFecha = fecha;
                    this.modalEntradaIdx = idx;

                    // Obtener la última hora_fin del día si se va a añadir uno nuevo
                    let minHoraInicio = '';
                    if (
                        idx === null &&
                        user.entradas &&
                        user.entradas[fecha] &&
                        Array.isArray(user.entradas[fecha]) &&
                        user.entradas[fecha].length
                    ) {
                        // Buscar la mayor hora_fin de los horarios existentes ese día
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

                    // Guardar el mínimo para validación posterior
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
                    // Validar que la hora de inicio no sea menor que la última hora_fin del día
                    if (this.modalEntradaIdx === null && this.minHoraInicio) {
                        if (this.modalHoraInicio < this.minHoraInicio) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Hora inválida',
                                text: 'La hora de inicio debe ser igual o posterior a la hora de salida del último horario de ese día: ' + this.minHoraInicio
                            });
                            return;
                        }
                    }
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
                    this.cerrarModalHorario();
                },
                guardarEntradas() {
                    // Construir payload plano para enviar al backend
                    let payload = [];

                    // Siempre enviar todos los usuarios, aunque no tengan horarios
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
                        // Si la respuesta no es JSON, mostrar error amigable
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
                            window.location.reload(); // Recargar para reflejar los cambios guardados
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
                        // Mantener los horarios del usuario anterior
                        let oldEntradas = this.selectedUsers[this.cambiarUsuarioIdx].entradas || {};
                        this.selectedUsers[this.cambiarUsuarioIdx] = {
                            ...user,
                            entradas: oldEntradas
                        };
                    }
                    this.cerrarCambiarUsuario();
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
</x-app-layout>
