<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'regex:/^[0-9]{8}[A-Z]$/'],
            'telefono' => ['required', 'string', 'regex:/^[0-9]{9}$/'],
            'es_jefe' => ['boolean', 'max:255'],
            'apodo' => ['nullable','string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'apellidos' => $input['apellidos'],
            'dni' => $input['dni'],
            'telefono' => $input['telefono'],
            'es_jefe' => $input['es_jefe'],
            'apodo' => $input['apodo'] ?? null,
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
