<?php

namespace App\Providers;

use App\Repositories\EmployeeRepository;
use App\Repositories\EmployeeRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
    }
}