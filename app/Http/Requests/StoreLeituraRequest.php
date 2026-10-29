<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeituraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_id' => ['required', 'string', 'max:64'],
            'cell_1' => ['sometimes', 'nullable', 'numeric'],
            'cell_2' => ['sometimes', 'nullable', 'numeric'],
            'cell_3' => ['sometimes', 'nullable', 'numeric'],
            'cell_4' => ['sometimes', 'nullable', 'numeric'],
            'total' => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'device_id.required' => 'O identificador da balança é obrigatório.',
            'device_id.string' => 'O identificador da balança deve ser um texto.',
            'device_id.max' => 'O identificador da balança não pode ter mais de 64 caracteres.',
            'total.required' => 'O total é obrigatório.',
            'total.numeric' => 'O total deve ser numérico.',
        ];
    }
}
