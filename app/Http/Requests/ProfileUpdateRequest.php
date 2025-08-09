<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Permitir que el usuario autorizado haga la petición
    }

    public function rules()
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:25',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'email' => [
                'required',
                'string',
                'regex:/^[^@]+@[^@]+\.[^@]+$/',
                'max:255',
                Rule::unique('users')->ignore($this->user()->id),
            ],
        ];

        if ($this->user()->tipo === 'c') {
            // Solo clientes pueden enviar teléfono, que puede ser nulo o con formato válido
            $rules['telefono'] = [
                'nullable',
                'string',
                'regex:/^([67]\d{8}|[67]\d{2} \d{3} \d{3}|[67]\d{2} \d{2} \d{2} \d{2})$/'
            ];
        } else {
            // Otros tipos no pueden enviar teléfono
            $rules['telefono'] = ['prohibited'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede exceder los 25 caracteres.',
            'name.unique' => 'Este nombre ya está en uso.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.regex' => 'Debe ingresar un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está en uso.',
            'telefono.regex' => 'El formato del teléfono no es válido.',
            'telefono.prohibited' => 'No tienes permiso para modificar el teléfono.',
        ];
    }
}
