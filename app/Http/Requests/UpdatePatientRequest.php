<?php

namespace App\Http\Requests;

use App\Rules\CnsRule;
use App\Rules\CpfRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $patientId = $this->route('patient')?->id ?? $this->route('patient');

        return [
            'name' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', new CpfRule(), 'unique:patients,cpf,' . $patientId],
            'cns' => ['required', 'string', new CnsRule(), 'unique:patients,cns,' . $patientId],
            'birth_date' => ['required', 'date', 'before_or_equal:today'],
            'gender' => ['required', 'in:M,F,O'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9]{10,11}$/'],
            'address_id' => ['required', 'exists:addresses,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.max' => 'O nome deve ter no máximo 255 caracteres.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'cns.unique' => 'Este CNS já está cadastrado.',
            'birth_date.required' => 'A data de nascimento é obrigatória.',
            'birth_date.date' => 'A data de nascimento não é válida.',
            'birth_date.before_or_equal' => 'A data de nascimento não pode ser no futuro.',
            'gender.required' => 'O sexo é obrigatório.',
            'gender.in' => 'O sexo deve ser M, F ou O.',
            'phone.max' => 'O telefone deve ter no máximo 11 caracteres.',
            'address_id.required' => 'O endereço é obrigatório.',
            'address_id.exists' => 'O endereço selecionado não existe.',
        ];
    }
}
