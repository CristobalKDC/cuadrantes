<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Perfil
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
         <!-- Botón Volver al menú principal -->
         @php
            $esJefe = auth()->check() && auth()->user()->es_jefe == 1;
        @endphp

        <a href="{{ $esJefe ? route('dashboard') : route('vista.usuario') }}" class="inline-block mb-6 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition text-sm">
            ← Volver al menú principal
        </a>

        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
            @livewire('profile.update-profile-information-form')

            

            <x-section-border />
        @endif

        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
            <div class="mt-10 sm:mt-0">
                @livewire('profile.update-password-form', ['titulo' => 'Actualizar contraseña', 'actual' => 'Contraseña actual', 'nueva' => 'Nueva contraseña', 'confirmar' => 'Confirmar contraseña', 'guardar' => 'Guardar'])
            </div>

            <x-section-border />
        @endif

        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            <div class="mt-10 sm:mt-0">
                @livewire('profile.two-factor-authentication-form', ['titulo' => 'Autenticación en dos factores', 'activar' => 'Activar', 'desactivar' => 'Desactivar'])
            </div>

            <x-section-border />
        @endif

        <div class="mt-10 sm:mt-0">
            @livewire('profile.logout-other-browser-sessions-form', ['titulo' => 'Cerrar otras sesiones del navegador', 'guardar' => 'Cerrar sesiones'])
        </div>

        @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
            <x-section-border />

            <div class="mt-10 sm:mt-0">
                @livewire('profile.delete-user-form', ['titulo' => 'Eliminar cuenta', 'eliminar' => 'Eliminar cuenta'])
            </div>
        @endif
    </div>
</x-app-layout>
