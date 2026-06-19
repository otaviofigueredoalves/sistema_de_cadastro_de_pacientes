<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'cpf' => $this->generateValidCpf(),
            'cns' => $this->generateValidCns(),
            'birth_date' => fake()->dateTimeBetween('-80 years', '-1 day')->format('Y-m-d'),
            'gender' => fake()->randomElement(['M', 'F', 'O']),
            'phone' => fake()->numerify('###########'),
            'address_id' => Address::factory(),
        ];
    }

    /**
     * Generate a mathematically valid CPF string.
     */
    protected function generateValidCpf(): string
    {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = rand(0, 9);

        $d1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
        $d1 = 11 - (abs($d1) % 11);
        if ($d1 >= 10) {
            $d1 = 0;
        }

        $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
        $d2 = 11 - (abs($d2) % 11);
        if ($d2 >= 10) {
            $d2 = 0;
        }

        return "{$n1}{$n2}{$n3}{$n4}{$n5}{$n6}{$n7}{$n8}{$n9}{$d1}{$d2}";
    }

    /**
     * Generate a mathematically valid provisional CNS string.
     */
    protected function generateValidCns(): string
    {
        $cns = (string) rand(7, 9);
        for ($i = 0; $i < 13; $i++) {
            $cns .= rand(0, 9);
        }

        $soma = 0;
        for ($i = 0; $i < 14; $i++) {
            $soma += ((int) $cns[$i]) * (15 - $i);
        }

        $resto = $soma % 11;
        $dv = 11 - $resto;
        if ($dv === 11) {
            $dv = 0;
        }
        if ($dv === 10) {
            // Se der 10 no provisório, regra manda recalcular, mas pra teste é mais fácil refazer
            return $this->generateValidCns();
        }

        return $cns.$dv;
    }
}
