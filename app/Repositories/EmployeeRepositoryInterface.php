<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface EmployeeRepositoryInterface
{
    public function create(array $data);
    public function firstOrCreate(array $attributes, array $values = []);
    public function updateOrCreate(array $attributes, array $values = []);
    public function getAll(array $queryParams = []): Collection;
    function getOne(array $queryParams = []) : ?Model;
}