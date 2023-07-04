<?php

namespace App\Services;

use App\Actions\GetEmployeeAction;
use App\Actions\GetEmployeeHierarchyAction;
use App\Actions\StoreEmployeeAction;
use App\Actions\UpdateEmployeeAction;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class EmployeeService
{

    public function __construct(
        private GetEmployeeAction $getEmployeeAction,
        private GetEmployeeHierarchyAction $getEmployeeHierarchyAction,
        private StoreEmployeeAction $storeEmployeeAction,
        private UpdateEmployeeAction $updateEmployeeAction,
    ) {
    }

    public function findByName(string $name): ?Employee
    {
        return $this->getEmployeeAction->execute(['name' => $name]);
    }

    public function createOrUpdate(array $data): Employee
    {
        $employee = $this->findByName(data_get($data, 'name'));

        if ($employee) {
            $this->updateEmployeeAction->execute($employee, $data);
            return $employee;
        }

        return $this->storeEmployeeAction->execute($data);
    }

    public function createOrUpdateEmployees(array $employeesData)
    {
        DB::transaction(function () use ($employeesData) {
            collect($employeesData)->each(function ($employeeData) {
                $supervisorName = data_get($employeeData, 'supervisor');
                $supervisor = $this->createOrUpdate([
                    'name' => $supervisorName
                ]);
                $this->createOrUpdate([
                    'name' => $employeeData['name'],
                    'supervisor_id' => $supervisor->id,
                ]);
            });
        });
    }

    public function getHierarchy(?Employee $rootEmployee = null): array
    {
        $rootEmployee = $rootEmployee
            ?: $this->getEmployeeAction->execute(['supervisor_id' => null]);

        return !$rootEmployee ? [] : $this->getEmployeeHierarchyAction->execute($rootEmployee);
    }
}
