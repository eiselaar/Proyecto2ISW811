<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación para almacenar horarios programados
 */
class ScheduleStoreRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'day_of_week' => [
                'required',     // Obligatorio
                'integer',      // Número entero
                'between:0,6'   // 0 (domingo) a 6 (sábado)
            ],
            'time' => [
                'required',          // Obligatorio
                'date_format:H:i'    // Formato hora (HH:mm)
            ],
            'is_active' => [
                'boolean'      // true/false
            ],
        ];
    }
}