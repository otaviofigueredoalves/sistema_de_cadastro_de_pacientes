<?php

namespace App\Repositories;

use App\Models\Address;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AddressRepository
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = Address::query();

        // Search
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('street', 'like', "%{$search}%")
                    ->orWhere('neighborhood', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('zip_code', 'like', "%{$search}%");
            });
        }

        // Filter by state
        if (! empty($filters['state'])) {
            $query->where('state', $filters['state']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'id';
        $sortDir = $filters['sort_dir'] ?? 'desc';

        $allowedSorts = ['id', 'street', 'neighborhood', 'city', 'state', 'zip_code', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination
        $perPage = (int) ($filters['per_page'] ?? 15);
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 15;
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?Address
    {
        return Address::find($id);
    }

    public function create(array $data): Address
    {
        return Address::create($data);
    }

    public function update(Address $address, array $data): bool
    {
        return $address->update($data);
    }

    public function delete(Address $address): bool
    {
        return $address->delete();
    }

    public function hasPatients(Address $address): bool
    {
        return $address->patients()->exists();
    }
}
