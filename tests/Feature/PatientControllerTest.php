<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PatientControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_patients(): void
    {
        Patient::factory()->count(3)->create();

        $response = $this->getJson('/api/pacientes');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_patient_and_logs_creation(): void
    {
        // RN-04
        Log::shouldReceive('channel')->with('daily')->andReturnSelf();
        Log::shouldReceive('info')->with('Paciente criado', \Mockery::any());

        $address = Address::factory()->create();

        $payload = [
            'name' => 'Paciente Teste',
            'cpf' => Patient::factory()->make()->cpf,
            'cns' => '123456789012345',
            'birth_date' => '1990-01-01',
            'gender' => 'O',
            'phone' => '11999999999',
            'address_id' => $address->id,
        ];

        $response = $this->postJson('/api/pacientes', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Paciente Teste']);
    }

    public function test_fails_if_cpf_is_invalid(): void
    {
        // RN-05
        $address = Address::factory()->create();

        $payload = [
            'name' => 'Paciente Teste',
            'cpf' => '11111111111',
            'cns' => '123456789012345',
            'birth_date' => '1990-01-01',
            'gender' => 'O',
            'address_id' => $address->id,
        ];

        $response = $this->postJson('/api/pacientes', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cpf']);
    }

    public function test_fails_if_cpf_is_not_unique(): void
    {
        // RN-01
        $existingPatient = Patient::factory()->create();
        $address = Address::factory()->create();

        $payload = [
            'name' => 'Paciente Teste 2',
            'cpf' => $existingPatient->cpf,
            'cns' => '123456789012345',
            'birth_date' => '1990-01-01',
            'gender' => 'M',
            'address_id' => $address->id,
        ];

        $response = $this->postJson('/api/pacientes', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cpf']);
    }

    public function test_fails_if_cns_is_not_unique(): void
    {
        // RN-02
        $existingPatient = Patient::factory()->create();
        $address = Address::factory()->create();

        $payload = [
            'name' => 'Paciente Teste 2',
            'cpf' => Patient::factory()->make()->cpf,
            'cns' => $existingPatient->cns,
            'birth_date' => '1990-01-01',
            'gender' => 'M',
            'address_id' => $address->id,
        ];

        $response = $this->postJson('/api/pacientes', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cns']);
    }

    public function test_fails_if_cns_is_not_15_digits(): void
    {
        // RN-06
        $address = Address::factory()->create();

        $payload = [
            'name' => 'Paciente',
            'cpf' => Patient::factory()->make()->cpf,
            'cns' => '12345', // Inválido
            'birth_date' => '1990-01-01',
            'gender' => 'M',
            'address_id' => $address->id,
        ];

        $response = $this->postJson('/api/pacientes', $payload);
        $response->assertStatus(422)->assertJsonValidationErrors(['cns']);
    }

    public function test_fails_if_birth_date_in_future(): void
    {
        // RN-08
        $address = Address::factory()->create();

        $payload = [
            'name' => 'Paciente Futuro',
            'cpf' => Patient::factory()->make()->cpf,
            'cns' => '123456789012345',
            'birth_date' => now()->addDay()->format('Y-m-d'),
            'gender' => 'F',
            'address_id' => $address->id,
        ];

        $response = $this->postJson('/api/pacientes', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['birth_date']);
    }

    public function test_fails_if_gender_is_invalid(): void
    {
        // RN-09
        $address = Address::factory()->create();

        $payload = [
            'name' => 'Paciente',
            'cpf' => Patient::factory()->make()->cpf,
            'cns' => '123456789012345',
            'birth_date' => '1990-01-01',
            'gender' => 'X', // Inválido
            'address_id' => $address->id,
        ];

        $response = $this->postJson('/api/pacientes', $payload);
        $response->assertStatus(422)->assertJsonValidationErrors(['gender']);
    }
}
