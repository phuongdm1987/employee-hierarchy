<?php

namespace App\Repositories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface EmployeeRepositoryInterface
{
    public function create(array $data): Model;
    public function update(Employee $employee, array $data): bool;
    public function getAll(array $queryParams = []): Collection;
    public function getOne(array $queryParams = []) : ?Model;
}