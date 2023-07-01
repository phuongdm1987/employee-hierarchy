<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetEmployeeRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{

    public function __construct(private EmployeeService $employeeService)
    {
    }

    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $data = $request->getValidatedData();
        $this->employeeService->createOrUpdateEmployees($data);

        return response()->json(['message' => 'Employees added successfully'], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $hierarchy = $this->employeeService->getHierarchy();

        return response()->json($hierarchy);
    }

    public function show($name, GetEmployeeRequest $request): JsonResponse
    {
        $employee = $this->employeeService->findByName($name);
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $hierarchy = $this->employeeService->getHierarchyByEmployee($employee, $employee->supervisor, $request->getLevel());

        return response()->json($hierarchy);
    }
}
