<?php

namespace App\Repositories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class AbstractRepository
{
    abstract protected function getModel() : Model;
    public function create(array $data): Model
    {
        return $this->getModel()->create($data);
    }

    public function update(Employee $employee, array $data): bool
    {
        return $employee->update($data);
    }

    public function getAll(array $queryParams = []): Collection
    {
        $query = $this->getModel()->query();
        $this->buildQuery($query, $queryParams);

        return $query->get();
    }

    public function getOne(array $queryParams = []) : ?Model
    {
        $query = $this->getModel()->query();
        $this->buildQuery($query, $queryParams);

        return $query->first();
    }

    protected function buildQuery(Builder $query, array $params)
    {
        $this->applyFilter($query, $params);
    }

    protected function applyFilter(Builder $query, array $params)
    {
        foreach ($params as $field => $value) {
            $scopeMethodName = 'scope' . ucfirst($field);
            if (method_exists($this->getModel(), $scopeMethodName)) {
                $query->{$field}($value);
                continue;
            }

            if (!$this->isColumnAvailable($field)) {
                continue;
            }

            $query->where($field, $value);
        }
    }

    protected function getSearchFields() : array
    {
        return $this->getModel()->getFillable();
    }

    private function isColumnAvailable(string $field): bool
    {
        return in_array($field, $this->getSearchFields());
    }
}