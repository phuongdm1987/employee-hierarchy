<?php

namespace App\Services;

use App\Models\Employee;
use App\Repositories\EmployeeRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    private $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function findByName(string $name): ?Employee
    {
        return $this->employeeRepository->getOne(['name' => $name]);
    }

    public function createOrUpdateEmployees(array $employeesData)
    {
        DB::transaction(function () use ($employeesData) {
            collect($employeesData)->each(function ($employeeData) {
                $supervisorName = Arr::get($employeeData, 'supervisor');
                $supervisor = $this->employeeRepository->firstOrCreate(['name' => $supervisorName]);
                $this->employeeRepository->updateOrCreate(
                    [
                        'name' => $employeeData['name'],
                    ],
                    [
                        'supervisor_id' => $supervisor->id,
                    ]
                );
            });
        });
    }

    public function getHierarchy(?Employee $rootEmployee = null): array
    {
        $rootEmployee = $rootEmployee
            ?: $this->employeeRepository->getOne(['supervisor_id' => null]);

        if (!$rootEmployee) {
            return [];
        }

        $hierarchy = $this->buildHierarchy($rootEmployee);

        return [$rootEmployee->name => $hierarchy];
    }

    public function getHierarchyByEmployee(
        Employee $employee,
        ?Employee $supervisor = null,
        ?int $levels = null,
        array &$hierarchy = []
    ): array {
        $hierarchy[$employee->name] = $hierarchy[$employee->name] ?? [];
        $isNeedMoreEmployee = ($levels === null || $levels > 0) && $employee->supervisor;
        $levels = $levels === null ? null : $levels - 1;

        if (!$isNeedMoreEmployee) {
            return $hierarchy;
        }

        $hierarchy[$supervisor->name] = $hierarchy;
        unset($hierarchy[$employee->name]);
        $this->getHierarchyByEmployee($supervisor, $supervisor->supervisor, $levels, $hierarchy);

        return $hierarchy;
    }

    private function buildHierarchy($employee): array
    {
        $hierarchy = [];

        foreach ($employee->subordinates as $subordinate) {
            $hierarchy[$subordinate->name] = $this->buildHierarchy($subordinate);
        }

        return $hierarchy;
    }
}
