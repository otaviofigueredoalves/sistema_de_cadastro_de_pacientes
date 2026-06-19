<?php

namespace App\Services;

use App\Models\Patient;
use App\Repositories\PatientRepository;
use Illuminate\Support\Facades\Log;

class PatientService
{
    public function __construct(
        protected PatientRepository $patientRepository
    ) {}

    public function getAll(array $filters = [])
    {
        return $this->patientRepository->getAll($filters);
    }

    public function findById(int $id): ?Patient
    {
        return $this->patientRepository->findById($id);
    }

    public function create(array $data): Patient
    {
        if (isset($data['cpf'])) {
            $data['cpf'] = preg_replace('/[^0-9]/', '', $data['cpf']);
        }

        $patient = $this->patientRepository->create($data);
        Log::channel('daily')->info('Paciente criado', ['patient_id' => $patient->id]);

        return $patient;
    }

    public function update(Patient $patient, array $data): bool
    {
        if (isset($data['cpf'])) {
            $data['cpf'] = preg_replace('/[^0-9]/', '', $data['cpf']);
        }

        $updated = $this->patientRepository->update($patient, $data);
        Log::channel('daily')->info('Paciente atualizado', ['patient_id' => $patient->id]);

        return $updated;
    }

    public function delete(Patient $patient): bool
    {
        $deleted = $this->patientRepository->delete($patient);
        Log::channel('daily')->info('Paciente excluído', ['patient_id' => $patient->id]);

        return $deleted;
    }
}
