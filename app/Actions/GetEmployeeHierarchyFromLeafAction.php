<?php

namespace App\Actions;

use App\Models\Employee;

class GetEmployeeHierarchyFromLeafAction
{
    public function execute(Employee $leafEmployee, ?int $levels = null) : array
    {
        return $this->getHierarchyByEmployee($leafEmployee, $leafEmployee->supervisor, $levels);
    }

    private function getHierarchyByEmployee(
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
}