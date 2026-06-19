<?php

namespace App\Repositories;

use App\Models\Patient;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PatientRepository
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = Patient::with('address');

        // Search by name, CPF, or CNS
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('cpf', 'like', "%{$search}%")
                    ->orWhere('cns', 'like', "%{$search}%");
            });
        }

        // Filter by gender
        if (! empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'id';
        $sortDir = $filters['sort_dir'] ?? 'desc';

        $allowedSorts = ['id', 'name', 'cpf', 'cns', 'birth_date', 'gender', 'created_at'];
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

    public function findById(int $id): ?Patient
    {
        return Patient::with('address')->find($id);
    }

    public function create(array $data): Patient
    {
        return Patient::create($data);
    }

    public function update(Patient $patient, array $data): bool
    {
        return $patient->update($data);
    }

    public function delete(Patient $patient): bool
    {
        return $patient->delete();
    }
}
