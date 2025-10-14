<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'apellido' => ['nullable', 'string', 'max:255'],
            'contacto' => ['nullable', 'string', 'max:20'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'file', 'max:5120'], // Máximo 5MB
        ];
    }
}
