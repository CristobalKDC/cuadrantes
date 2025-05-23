<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <img src="{{ asset('img/ico.jpeg') }}" alt="Logo" class="h-16 w-16 mx-auto rounded-full"> <!-- Bordes redondeados -->
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Nombre') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="apellidos" value="Apellidos" />
                <x-input id="apellidos" class="block mt-1 w-full" type="text" name="apellidos" :value="old('apellidos')" required />
            </div>

            <div class="mt-4">
                <x-label for="dni" value="DNI" />
                <x-input id="dni" type="text" name="dni" class="mt-1 block w-full uppercase" :value="old('dni')" oninput="this.value = this.value.toUpperCase()" />

            </div>

            <div class="mt-4">
                <x-label for="telefono" value="Teléfono" />
                <x-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono')" required />
            </div>

            <div x-data="{ mostrarApodo: false }" class="mt-4">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" x-model="mostrarApodo"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    
                    <span class="text-sm text-gray-600 flex items-center">
                        Apodo

                        <div class="relative ml-2 group cursor-pointer">
                            <div class="w-4 h-4 rounded-full bg-gray-300 text-xs font-bold text-gray-800 flex items-center justify-center">?</div>
                            <div class="absolute left-1/2 -translate-x-1/2 mt-2 w-48 bg-white text-xs text-gray-700 border border-gray-300 rounded p-2 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10">
                                Quiero que se me mencione en los cuadrantes por mi apodo.
                            </div>
                        </div>
                    </span>
                </label>

                <div x-show="mostrarApodo" class="mt-2">
                    <x-label for="apodo" value="Apodo" />
                    <x-input id="apodo" type="text" name="apodo" class="mt-1 block w-full"
                            :value="old('apodo')" />
                </div>
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <label class="flex items-center">
                    <!-- Si no se marca, se enviará '0' -->
                    <input type="hidden" name="es_jefe" value="0">

                    <!-- Si se marca, sobreescribe con '1' -->
                    <input type="checkbox" name="es_jefe" value="1"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />

                    <span class="ml-2 text-sm text-gray-600">Soy jefe/encargado</span>
                </label>
            </div>


            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-between mt-4">
                <a href="{{ url('/') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Volver') }}
                </a>
                <x-button class="ms-4">
                    {{ __('Registrarse') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
