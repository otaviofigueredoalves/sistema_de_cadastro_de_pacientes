<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'street' => ['required', 'string', 'max:255'],
            'zip_code' => ['required', 'string', 'regex:/^[0-9]{8}$/'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'size:2'],
        ];
    }

    public function messages(): array
    {
        return [
            'street.required' => 'A rua é obrigatória.',
            'street.max' => 'A rua deve ter no máximo 255 caracteres.',
            'zip_code.required' => 'O CEP é obrigatório.',
            'zip_code.regex' => 'O CEP deve conter exatamente 8 dígitos numéricos.',
            'neighborhood.required' => 'O bairro é obrigatório.',
            'neighborhood.max' => 'O bairro deve ter no máximo 255 caracteres.',
            'city.required' => 'A cidade é obrigatória.',
            'city.max' => 'A cidade deve ter no máximo 255 caracteres.',
            'state.required' => 'O estado é obrigatório.',
            'state.size' => 'O estado deve ter exatamente 2 caracteres.',
        ];
    }
}
