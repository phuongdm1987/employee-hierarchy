<?php

namespace App\Actions;

use App\Models\Employee;
use App\Repositories\EmployeeRepositoryInterface;

class UpdateEmployeeAction
{
    public function __construct(private EmployeeRepositoryInterface $repository)
    {

    }

    public function execute(Employee $employee, array $attributes): bool
    {
        return $this->repository->update($employee, $attributes);
    }
}