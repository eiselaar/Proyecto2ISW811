<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:280'],
            'platforms' => ['required', 'array', 'min:1'],
            'platforms.*' => ['required', 'string', 'in:twitter,reddit,mastodon'],
            'schedule_type' => ['required', 'string', 'in:now,queue,scheduled'],
            'scheduled_for' => ['required_if:schedule_type,scheduled', 'nullable', 'date', 'after:now'],
            'media' => ['nullable', 'array', 'max:4'],
            'media.*' => ['file', 'mimes:jpeg,png,gif', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'platforms.required' => 'Please select at least one platform.',
            'scheduled_for.after' => 'The scheduled time must be in the future.',
            'media.*.max' => 'Each image must not exceed 5MB.',
        ];
    }
}