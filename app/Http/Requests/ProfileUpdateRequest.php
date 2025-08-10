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
            // Solo clientes pueden enviar teléfono y CV
            $rules['telefono'] = [
                'nullable',
                'string',
                'regex:/^([67]\d{8}|[67]\d{2} \d{3} \d{3}|[67]\d{2} \d{2} \d{2} \d{2})$/'
            ];

            $rules['cv'] = [
                'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:2048', // máximo 2MB
            ];
        } else {
            // Otros tipos no pueden enviar teléfono ni CV
            $rules['telefono'] = ['prohibited'];
            $rules['cv'] = ['prohibited'];
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
            'cv.file' => 'El archivo del CV debe ser un archivo válido.',
            'cv.mimes' => 'El CV debe ser un archivo de tipo: pdf, doc o docx.',
            'cv.max' => 'El CV no puede superar los 2MB.',
            'cv.prohibited' => 'No tienes permiso para modificar el CV.',
        ];
    }
}
