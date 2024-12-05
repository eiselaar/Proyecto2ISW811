<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Clase para validar las peticiones de registro de usuarios
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determina si el usuario puede realizar esta petición
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Permite que cualquier usuario se registre
    }

    /**
     * Define las reglas de validación para el registro
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',     // Campo obligatorio
                'string',       // Debe ser texto
                'max:255'       // Máximo 255 caracteres
            ],
            'email' => [
                'required',     // Campo obligatorio
                'string',       // Debe ser texto
                'email',        // Formato válido de email
                'max:255',      // Máximo 255 caracteres
                'unique:users'  // No debe existir en la tabla users
            ],
            'password' => [
                'required',           // Campo obligatorio
                'confirmed',          // Debe coincidir con password_confirmation
                Password::defaults()  // Usa las reglas predeterminadas de contraseña
            ],
        ];
    }
}