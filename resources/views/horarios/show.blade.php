<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cuadrante: {{ $horario->titulo }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="cuadrante">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow rounded">
            <a href="{{ route('dashboard') }}" class="inline-block mb-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition text-sm">
                ← Volver al menú principal.
            </a>
            <!-- Contenedor con desplazamiento horizontal -->
            <div class="overflow-x-auto">
                <table class="table-auto min-w-max w-full text-center border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border p-2">Nombre</th>
                            @foreach($fechas as $fecha)
                                <th class="border p-2">
                                    {{ \Carbon\Carbon::parse($fecha)->translatedFormat('l d/m') }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="selectedUser in selectedUsers" :key="selectedUser.id">
                            <tr>
                                <td class="border p-2 font-semibold text-left" x-text="selectedUser.name"></td>
                                <template x-for="fecha in fechas">
                                    <td class="border p-2">-</td>
                                </template>
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
                <div class="bg-white p-6 rounded shadow-lg w-[50vw]">
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

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cuadrante', () => ({
                isUserModalOpen: false,
                userSearchQuery: '',
                users: @json($usuarios),
                selectedUsers: [], // Lista de usuarios seleccionados
                fechas: @json($fechas), // Fechas disponibles
                filteredUsers() {
                    if (this.userSearchQuery === '') return this.users;
                    return this.users.filter(user => 
                        user.name.toLowerCase().includes(this.userSearchQuery.toLowerCase()) ||
                        user.apellidos.toLowerCase().includes(this.userSearchQuery.toLowerCase()) || // Cambiado de lastname a apellidos
                        user.dni.toLowerCase().includes(this.userSearchQuery.toLowerCase())
                    );
                },
                openUserModal() {
                    this.isUserModalOpen = true;
                },
                closeUserModal() {
                    this.isUserModalOpen = false;
                },
                addUser(user) {
                    // Agregar usuario seleccionado a la lista
                    this.selectedUsers.push(user);
                    this.closeUserModal();
                }
            }));
        });
    </script>
</x-app-layout>
