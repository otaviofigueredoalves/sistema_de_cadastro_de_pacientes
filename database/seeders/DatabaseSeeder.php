<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $manualData = [
            [
                'name' => 'Maria Silva Costa',
                'cns' => '123456789012345',
                'gender' => 'F',
                'birth_date' => '1985-05-12',
                'phone' => '11987654321',
                'street' => 'Avenida Paulista, 1578',
                'neighborhood' => 'Bela Vista',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '01310200',
            ],
            [
                'name' => 'João Pedro Albuquerque',
                'cns' => '987654321098765',
                'gender' => 'M',
                'birth_date' => '1992-08-23',
                'phone' => '21999887766',
                'street' => 'Avenida Atlântica, 1702',
                'neighborhood' => 'Copacabana',
                'city' => 'Rio de Janeiro',
                'state' => 'RJ',
                'zip_code' => '22021001',
            ],
            [
                'name' => 'Ana Clara Ferreira',
                'cns' => '234567890123456',
                'gender' => 'F',
                'birth_date' => '1978-11-30',
                'phone' => '31988776655',
                'street' => 'Rua da Bahia, 1148',
                'neighborhood' => 'Centro',
                'city' => 'Belo Horizonte',
                'state' => 'MG',
                'zip_code' => '30160011',
            ],
            [
                'name' => 'Carlos Eduardo Santos',
                'cns' => '345678901234567',
                'gender' => 'M',
                'birth_date' => '2001-02-15',
                'phone' => '41977665544',
                'street' => 'Rua das Flores, 202',
                'neighborhood' => 'Centro',
                'city' => 'Curitiba',
                'state' => 'PR',
                'zip_code' => '80020120',
            ],
            [
                'name' => 'Juliana Mendes',
                'cns' => '456789012345678',
                'gender' => 'F',
                'birth_date' => '1995-07-07',
                'phone' => '61966554433',
                'street' => 'SQS 305 Bloco E, 101',
                'neighborhood' => 'Asa Sul',
                'city' => 'Brasília',
                'state' => 'DF',
                'zip_code' => '70352050',
            ],
            [
                'name' => 'Marcos Vinícius Gomes',
                'cns' => '567890123456789',
                'gender' => 'M',
                'birth_date' => '1988-09-18',
                'phone' => '71955443322',
                'street' => 'Avenida Oceânica, 2992',
                'neighborhood' => 'Barra',
                'city' => 'Salvador',
                'state' => 'BA',
                'zip_code' => '40170010',
            ],
            [
                'name' => 'Luiza de Sousa e Silva',
                'cns' => '678901234567890',
                'gender' => 'F',
                'birth_date' => '1965-04-25',
                'phone' => '85944332211',
                'street' => 'Avenida Beira Mar, 2500',
                'neighborhood' => 'Meireles',
                'city' => 'Fortaleza',
                'state' => 'CE',
                'zip_code' => '60165121',
            ],
            [
                'name' => 'Ricardo Oliveira Rocha',
                'cns' => '789012345678901',
                'gender' => 'M',
                'birth_date' => '1980-12-05',
                'phone' => '81933221100',
                'street' => 'Avenida Boa Viagem, 4000',
                'neighborhood' => 'Boa Viagem',
                'city' => 'Recife',
                'state' => 'PE',
                'zip_code' => '51021000',
            ],
            [
                'name' => 'Beatriz Carvalho',
                'cns' => '890123456789012',
                'gender' => 'F',
                'birth_date' => '2005-03-22',
                'phone' => '92922110099',
                'street' => 'Avenida Djalma Batista, 150',
                'neighborhood' => 'Chapada',
                'city' => 'Manaus',
                'state' => 'AM',
                'zip_code' => '69050010',
            ],
            [
                'name' => 'Fernanda Lima Nogueira',
                'cns' => '901234567890123',
                'gender' => 'O',
                'birth_date' => '1999-01-10',
                'phone' => '51911009988',
                'street' => 'Rua Padre Chagas, 300',
                'neighborhood' => 'Moinhos de Vento',
                'city' => 'Porto Alegre',
                'state' => 'RS',
                'zip_code' => '90570080',
            ],
        ];

        foreach ($manualData as $data) {
            $address = Address::create([
                'street' => $data['street'],
                'neighborhood' => $data['neighborhood'],
                'city' => $data['city'],
                'state' => $data['state'],
                'zip_code' => $data['zip_code'],
            ]);

            Patient::create([
                'name' => $data['name'],
                'cpf' => $this->generateValidCpf(),
                'cns' => $data['cns'],
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'phone' => $data['phone'],
                'address_id' => $address->id,
            ]);
        }
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
}
