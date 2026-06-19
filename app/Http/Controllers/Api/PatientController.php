<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Services\PatientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __construct(
        protected PatientService $patientService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $patients = $this->patientService->getAll($request->all());

        return response()->json($patients);
    }

    public function store(StorePatientRequest $request): JsonResponse
    {
        $patient = $this->patientService->create($request->validated());

        return response()->json($patient->load('address'), 201);
    }

    public function show(Patient $patient): JsonResponse
    {
        return response()->json($patient->load('address'));
    }

    public function update(UpdatePatientRequest $request, Patient $patient): JsonResponse
    {
        $this->patientService->update($patient, $request->validated());

        return response()->json($patient->fresh('address'));
    }

    public function destroy(Patient $patient): JsonResponse
    {
        $this->patientService->delete($patient);

        return response()->json(null, 204);
    }
}
