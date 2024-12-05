<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Clase para validar la creación de posts en redes sociales
 */
class PostStoreRequest extends FormRequest
{
    /**
     * Autorización de la petición
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para la creación de posts
     */
    public function rules(): array
    {
        return [
            'content' => [
                'required',    // Contenido obligatorio
                'string',      // Debe ser texto
                'max:280'      // Máximo 280 caracteres (estilo Twitter)
            ],
            'platforms' => [
                'required',    // Obligatorio seleccionar plataformas
                'array',       // Debe ser un array
                'min:1'        // Mínimo una plataforma
            ],
            'platforms.*' => [
                'required',    // Cada plataforma es obligatoria
                'string',      // Debe ser texto
                'in:linkedin,reddit,mastodon'  // Plataformas permitidas
            ],
            'schedule_type' => [
                'required',    // Tipo de programación obligatorio
                'string',      
                'in:now,queue,scheduled'  // Opciones válidas
            ],
            'scheduled_for' => [
                'required_if:schedule_type,scheduled',  // Obligatorio si es programado
                'nullable',    // Puede ser nulo
                'date',        // Debe ser fecha
                'after:now'    // Debe ser posterior a ahora
            ],
            'media' => [
                'nullable',    // Opcional
                'array',       // Debe ser array
                'max:4'        // Máximo 4 archivos
            ],
            'media.*' => [
                'file',        // Debe ser archivo
                'mimes:jpeg,png,gif',  // Formatos permitidos
                'max:5120'     // Máximo 5MB
            ],
        ];
    }

    /**
     * Mensajes personalizados de error
     */
    public function messages(): array
    {
        return [
            'platforms.required' => 'Please select at least one platform.',
            'scheduled_for.after' => 'The scheduled time must be in the future.',
            'media.*.max' => 'Each image must not exceed 5MB.',
        ];
    }
}