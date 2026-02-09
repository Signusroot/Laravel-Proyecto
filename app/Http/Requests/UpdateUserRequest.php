<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Autoriza este request (ajusta si usas políticas o gates).
     */
    public function authorize(): bool
    {
        // Ejemplo: return $this->user()->can('update', $this->route('user'));
        return true;
    }

    /**
     * Normaliza datos antes de validar para evitar falsos duplicados.
     */
    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('email')) {
            $merge['email'] = strtolower(trim((string) $this->input('email')));
        }

        if ($this->has('user')) {
            $merge['user'] = strtolower(trim((string) $this->input('user')));
        }

        if ($this->has('cedula')) {
            // Ajusta si manejas formato 'V12345678'/'E12345678'
            $merge['cedula'] = strtoupper(trim((string) $this->input('cedula')));
        }

        if (!empty($merge)) {
            $this->merge($merge);
        }
    }

    /**
     * Reglas de validación para actualizar usuario.
     *
     * - PATCH: validación parcial (solo campos enviados) con 'sometimes'.
     * - PUT: reemplazo completo; usa 'required' para exigir campos.
     */
    public function rules(): array
    {
        $userId = $this->route('user')->id;

        $rules = [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'lastname' => ['sometimes', 'required', 'string', 'max:255'],
            'user' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users')->ignore($userId),
            ],
            'cedula' => [
                'sometimes',
                'required',
                'string',
                'min:7',
                'max:20',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => ['sometimes', 'nullable', 'string', 'min:2', 'max:40'],
        ];

        if ($this->isMethod('put')) {
            // Para PUT, todos los campos son obligatorios
            foreach ($rules as $field => &$fieldRules) {
                if (in_array('sometimes', $fieldRules, true)) {
                    $fieldRules = array_filter($fieldRules, fn($r) => $r !== 'sometimes');
                    array_unshift($fieldRules, 'required');
                }
            }
        }

        return $rules;
    }

    /**
     * Mensajes personalizados.
     */
    public function messages(): array
    {
        return [
            'name.required'      => 'El nombre es obligatorio.',
            'name.max'           => 'El nombre no puede exceder :max caracteres.',

            'lastname.required'  => 'El apellido es obligatorio.',
            'lastname.max'       => 'El apellido no puede exceder :max caracteres.',

            'user.required'      => 'El usuario es obligatorio.',
            'user.string'        => 'El usuario debe ser un texto.',
            'user.max'           => 'El usuario no puede exceder :max caracteres.',
            'user.unique'        => 'El usuario ya está registrado.',

            'email.required'     => 'El correo es obligatorio.',
            'email.email'        => 'El formato de correo no es válido.',
            'email.unique'       => 'El correo ya está registrado.',

            'cedula.required'    => 'La cédula es obligatoria.',
            'cedula.string'      => 'La cédula debe ser un texto.',
            'cedula.min'         => 'La cédula debe tener al menos :min caracteres.',
            'cedula.max'         => 'La cédula no puede exceder :max caracteres.',
            'cedula.unique'      => 'La cédula ya está registrada.',

            'password.min'       => 'La contraseña debe tener al menos :min caracteres.',
            'password.max'       => 'La contraseña no puede exceder :max caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ];
    }

    /**
     * Atributos legibles para mensajes de error.
     */
    public function attributes(): array
    {
        return [
            'name'     => 'nombre',
            'lastname' => 'apellido',
            'user'     => 'usuario',
            'email'    => 'correo',
            'cedula'   => 'cédula',
            'password' => 'contraseña',
        ];
    }
}
