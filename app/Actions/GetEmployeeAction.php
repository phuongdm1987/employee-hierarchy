<?php

namespace App\Actions;

use App\Models\Employee;
use App\Repositories\EmployeeRepositoryInterface;

class GetEmployeeAction
{
    public function __construct(private EmployeeRepositoryInterface $repository)
    {
    }

    public function execute(array $params = []) : ?Employee
    {
        return $this->repository->getOne($params);
    }
}