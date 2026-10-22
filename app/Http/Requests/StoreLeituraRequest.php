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
            'cell_1' => ['required', 'numeric'],
            'cell_2' => ['required', 'numeric'],
            'cell_3' => ['required', 'numeric'],
            'cell_4' => ['required', 'numeric'],
            'total' => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'device_id.required' => 'O identificador da balança é obrigatório.',
            'device_id.string' => 'O identificador da balança deve ser um texto.',
            'device_id.max' => 'O identificador da balança não pode ter mais de 64 caracteres.',
            'cell_1.required' => 'O valor da célula 1 é obrigatório.',
            'cell_1.numeric' => 'O valor da célula 1 deve ser numérico.',
            'cell_2.required' => 'O valor da célula 2 é obrigatório.',
            'cell_2.numeric' => 'O valor da célula 2 deve ser numérico.',
            'cell_3.required' => 'O valor da célula 3 é obrigatório.',
            'cell_3.numeric' => 'O valor da célula 3 deve ser numérico.',
            'cell_4.required' => 'O valor da célula 4 é obrigatório.',
            'cell_4.numeric' => 'O valor da célula 4 deve ser numérico.',
            'total.required' => 'O total é obrigatório.',
            'total.numeric' => 'O total deve ser numérico.',
        ];
    }
}
