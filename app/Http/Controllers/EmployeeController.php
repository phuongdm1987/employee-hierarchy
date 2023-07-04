<?php

namespace App\Http\Controllers;

use App\Actions\GetEmployeeHierarchyFromLeafAction;
use App\Http\Requests\GetEmployeeRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{

    public function __construct(private EmployeeService $employeeService)
    {
    }

    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $data = $request->getValidatedData();
        $this->employeeService->createOrUpdateEmployees($data);

        return response()->json(['message' => 'Employees added successfully'], JsonResponse::HTTP_CREATED);
    }

    public function index(): JsonResponse
    {
        $hierarchy = $this->employeeService->getHierarchy();

        return response()->json($hierarchy);
    }

    public function show(
        $name,
        GetEmployeeRequest $request,
        GetEmployeeHierarchyFromLeafAction $getEmployeeHierarchyFromLeafAction
    ): JsonResponse {
        $employee = $this->employeeService->findByName($name);
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $hierarchy = $getEmployeeHierarchyFromLeafAction->execute($employee, $request->getLevel());

        return response()->json($hierarchy);
    }
}
