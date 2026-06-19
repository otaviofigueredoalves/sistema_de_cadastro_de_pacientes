<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;
use App\Services\AddressService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct(
        protected AddressService $addressService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $addresses = $this->addressService->getAll($request->all());

        return response()->json($addresses);
    }

    public function store(StoreAddressRequest $request): JsonResponse
    {
        $address = $this->addressService->create($request->validated());

        return response()->json($address, 201);
    }

    public function show(Address $address): JsonResponse
    {
        return response()->json($address);
    }

    public function update(UpdateAddressRequest $request, Address $address): JsonResponse
    {
        $this->addressService->update($address, $request->validated());

        return response()->json($address->fresh());
    }

    public function destroy(Address $address): JsonResponse
    {
        try {
            $this->addressService->delete($address);

            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
