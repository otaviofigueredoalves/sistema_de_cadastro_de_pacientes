<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_addresses(): void
    {
        Address::factory()->count(3)->create();

        $response = $this->getJson('/api/enderecos');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_address(): void
    {
        $payload = [
            'street' => 'Rua Teste',
            'zip_code' => '12345678',
            'neighborhood' => 'Bairro Teste',
            'city' => 'Cidade Teste',
            'state' => 'SP',
        ];

        $response = $this->postJson('/api/enderecos', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['street' => 'Rua Teste']);

        $this->assertDatabaseHas('addresses', $payload);
    }

    public function test_can_update_address(): void
    {
        $address = Address::factory()->create();

        $payload = [
            'street' => 'Rua Atualizada',
            'zip_code' => '87654321',
            'neighborhood' => 'Novo Bairro',
            'city' => 'Nova Cidade',
            'state' => 'RJ',
        ];

        $response = $this->putJson("/api/enderecos/{$address->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment(['street' => 'Rua Atualizada']);

        $this->assertDatabaseHas('addresses', $payload);
    }

    public function test_can_delete_address_without_patients(): void
    {
        $address = Address::factory()->create();

        $response = $this->deleteJson("/api/enderecos/{$address->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    public function test_cannot_delete_address_with_linked_patients(): void
    {
        // RN-03
        $address = Address::factory()->create();
        Patient::factory()->create(['address_id' => $address->id]);

        $response = $this->deleteJson("/api/enderecos/{$address->id}");

        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Não é possível excluir um endereço que possui pacientes vinculados.']);

        $this->assertDatabaseHas('addresses', ['id' => $address->id]);
    }
}
