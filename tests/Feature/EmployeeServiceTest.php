<?php

namespace Tests\Feature;

use App\Services\EmployeeService;
use Database\Seeders\EmployeeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeServiceTest extends TestCase
{
    use RefreshDatabase;
    protected EmployeeService $service;

    public function setup() : void
    {
        parent::setup();
        $this->service = app(EmployeeService::class);
    }

    /**
     * Test creating or updating employees.
     *
     * @return void
     */
    public function testCreateOrUpdateEmployees()
    {
        // Create a sample employees data array
        $employeesData = [
            [
                'name' => 'Employee 1',
                'supervisor' => 'Employee 3',
            ],
            [
                'name' => 'Employee 2',
                'supervisor' => 'Employee 3',
            ],
            [
                'name' => 'Employee 3',
                'supervisor' => 'Employee 4',
            ],
            [
                'name' => 'Employee 4',
                'supervisor' => 'Employee 5',
            ],
        ];

        // Call the createOrUpdateEmployees method
        $this->service->createOrUpdateEmployees($employeesData);
        $hierarchy = $this->service->getHierarchy();
        $this->assertEquals(
            [
                'Employee 5' => [
                    'Employee 4' => [
                        'Employee 3' => [
                            'Employee 2' => [],
                            'Employee 1' => [],
                        ]
                    ]
                ]
            ],
            $hierarchy
        );

        // Assert the database records
        $this->assertDatabaseCount('employees', 5);
    }

    public function testGetHierarchyByEmployee()
    {
        $this->seed(EmployeeSeeder::class);
        $employee = $this->service->findByName('Employee 3');
        $hierarchy = $this->service->getHierarchyByEmployee($employee, $employee->supervisor, 2);

        $this->assertEquals([
                'Employee 5' => [
                    'Employee 4' => [
                        'Employee 3' => []
                    ]
                ]
            ],
            $hierarchy
        );

        $hierarchy = $this->service->getHierarchyByEmployee($employee, $employee->supervisor);
        $this->assertEquals([
                'Employee 5' => [
                    'Employee 4' => [
                        'Employee 3' => []
                    ]
                ]
            ],
            $hierarchy
        );

        $hierarchy = $this->service->getHierarchyByEmployee($employee, $employee->supervisor, 1);
        $this->assertEquals([
                'Employee 4' => [
                    'Employee 3' => []
                ]
            ],
            $hierarchy
        );
    }
}
