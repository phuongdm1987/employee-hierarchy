<?php

namespace App\Repositories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class EmployeeRepository extends AbstractRepository implements EmployeeRepositoryInterface
{
    public function __construct(private Employee $model)
    {
    }

    protected function getModel() : Model
    {
        return $this->model;
    }
}