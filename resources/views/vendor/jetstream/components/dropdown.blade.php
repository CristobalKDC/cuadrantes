{{-- Traducción manual de los textos del menú Jetstream --}}
{{-- Si este archivo no existe, crea la carpeta y el archivo y pega este contenido --}}

<x-jet-dropdown align="right" width="48">
    <x-slot name="trigger">
        {{-- ...existing code... --}}
    </x-slot>

    <x-slot name="content">
        <!-- Account Management -->
        <div class="block px-4 py-2 text-xs text-gray-400">
            {{ __('Gestión de cuenta') }}
        </div>

        <x-jet-dropdown-link href="{{ route('profile.show') }}">
            {{ __('Perfil') }}
        </x-jet-dropdown-link>

        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
            <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                {{ __('Tokens de API') }}
            </x-jet-dropdown-link>
        @endif

        <div class="border-t border-gray-100"></div>

        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <x-jet-dropdown-link href="{{ route('logout') }}"
                     onclick="event.preventDefault();
                                this.closest('form').submit();">
                {{ __('Cerrar sesión') }}
            </x-jet-dropdown-link>
        </form>
    </x-slot>
</x-jet-dropdown>