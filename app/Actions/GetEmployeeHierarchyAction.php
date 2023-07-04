<?php

namespace App\Actions;

use App\Models\Employee;
use App\Repositories\EmployeeRepositoryInterface;

class GetEmployeeHierarchyAction
{
    public function __construct(private EmployeeRepositoryInterface $repository)
    {
    }

    public function execute(Employee $rootEmployee) : array
    {
        $hierarchy = $this->buildHierarchy($rootEmployee);

        return [$rootEmployee->name => $hierarchy];
    }

    private function buildHierarchy(Employee $employee): array
    {
        $hierarchy = [];

        foreach ($employee->subordinates as $subordinate) {
            $hierarchy[$subordinate->name] = $this->buildHierarchy($subordinate);
        }

        return $hierarchy;
    }
}