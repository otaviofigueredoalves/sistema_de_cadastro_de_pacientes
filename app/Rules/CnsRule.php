<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CnsRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cns = preg_replace('/[^0-9]/', '', (string) $value);

        if (strlen($cns) !== 15) {
            $fail('O CNS deve conter exatamente 15 dígitos numéricos válidos.');

            return;
        }

        if (in_array($cns[0], ['1', '2'])) {
            if (! $this->validateDefinitiveCns($cns)) {
                $fail('O CNS informado é matematicamente inválido.');
            }
        } elseif (in_array($cns[0], ['7', '8', '9'])) {
            if (! $this->validateProvisionalCns($cns)) {
                $fail('O CNS informado é matematicamente inválido.');
            }
        } else {
            $fail('O CNS informado possui um formato desconhecido.');
        }
    }

    private function validateDefinitiveCns(string $cns): bool
    {
        $pis = substr($cns, 0, 11);
        $soma = 0;

        for ($i = 0; $i < 11; $i++) {
            $soma += ((int) $pis[$i]) * (15 - $i);
        }

        $resto = $soma % 11;
        $dv = 11 - $resto;

        if ($dv === 11) {
            $dv = 0;
        }

        if ($dv === 10) {
            $soma = 0;
            $pis = substr($cns, 0, 11).'001';

            for ($i = 0; $i < 14; $i++) {
                $soma += ((int) $pis[$i]) * (15 - $i);
            }

            $resto = $soma % 11;
            $dv = 11 - $resto;
            $resultado = $pis.$dv;
        } else {
            $resultado = $pis.'000'.$dv;
        }

        return $cns === $resultado;
    }

    private function validateProvisionalCns(string $cns): bool
    {
        $soma = 0;

        for ($i = 0; $i < 15; $i++) {
            $soma += ((int) $cns[$i]) * (15 - $i);
        }

        return $soma % 11 === 0;
    }
}
