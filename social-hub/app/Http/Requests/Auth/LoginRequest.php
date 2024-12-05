<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Clase para validar las peticiones de login
 * Extiende de FormRequest para manejar la validación automática
 */
class LoginRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta petición
     * @return bool true si está autorizado, false si no
     */
    public function authorize(): bool
    {
        return true; // Permite que cualquier usuario intente hacer login
    }

    /**
     * Define las reglas de validación para los campos del formulario
     * @return array Reglas de validación para cada campo
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',  // El campo debe estar presente
                'string',    // Debe ser una cadena de texto
                'email'      // Debe tener formato válido de email
            ],
            'password' => [
                'required', // La contraseña es obligatoria
                'string'   // Debe ser una cadena de texto
            ],
            'remember' => [
                'boolean'  // Campo opcional para "recordarme" (true/false)
            ],
        ];
    }
}