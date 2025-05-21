<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        // Normalizar el teléfono: quitar espacios si existe
        if (isset($input['telefono'])) {
            $input['telefono'] = preg_replace('/\s+/', '', $input['telefono']);
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'apodo' => ['nullable', 'string', 'max:255'],
            'dni' => ['required', 'regex:/^[0-9]{8}[A-Z]$/'],
            'telefono' => ['nullable', 'regex:/^\d{9}$/'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ], [
            'telefono.regex' => 'El teléfono debe tener exactamente 9 dígitos numéricos.',
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'apellidos' => $input['apellidos'],
                'apodo' => $input['apodo'],
                'dni' => $input['dni'],
                'telefono' => $input['telefono'] ?? null,
                'email' => $input['email'],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'apellidos' => $input['apellidos'],
            'apodo' => $input['apodo'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
