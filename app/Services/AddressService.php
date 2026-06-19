<?php

namespace App\Services;

use App\Models\Address;
use App\Repositories\AddressRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class AddressService
{
    public function __construct(
        protected AddressRepository $addressRepository
    ) {}

    public function getAll(array $filters = [])
    {
        return $this->addressRepository->getAll($filters);
    }

    public function findById(int $id): ?Address
    {
        return $this->addressRepository->findById($id);
    }

    public function create(array $data): Address
    {
        $address = $this->addressRepository->create($data);
        Log::channel('daily')->info('Endereço criado', ['address_id' => $address->id]);

        return $address;
    }

    public function update(Address $address, array $data): bool
    {
        $updated = $this->addressRepository->update($address, $data);
        Log::channel('daily')->info('Endereço atualizado', ['address_id' => $address->id]);

        return $updated;
    }

    public function delete(Address $address): bool
    {
        if ($this->addressRepository->hasPatients($address)) {
            throw new Exception('Não é possível excluir um endereço que possui pacientes vinculados.', 400);
        }

        $deleted = $this->addressRepository->delete($address);
        Log::channel('daily')->info('Endereço excluído', ['address_id' => $address->id]);

        return $deleted;
    }
}
