<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array representing the employee-supervisor relationships
        $employees = [
            ['name' => 'Employee 1', 'supervisor' => 'Employee 3'],
            ['name' => 'Employee 2', 'supervisor' => 'Employee 3'],
            ['name' => 'Employee 3', 'supervisor' => 'Employee 4'],
            ['name' => 'Employee 4', 'supervisor' => 'Employee 5'],
        ];

        $service = app(EmployeeService::class);
        $service->createOrUpdateEmployees($employees);
    }
}
