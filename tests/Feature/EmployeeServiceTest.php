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

    public function testFindByName(): void
    {
        $this->seed(EmployeeSeeder::class);

        $employee = $this->service->findByName('Employee 3');

        $this->assertEquals('Employee 3', $employee->name);
        $this->assertEquals('Employee 4', $employee->supervisor->name);

        $subordinates = $employee->subordinates->pluck('name')->toArray();
        $this->assertContains('Employee 1', $subordinates);
        $this->assertContains('Employee 2', $subordinates);
    }

    public function testCreateOrUpdate() : void
    {
        $this->seed(EmployeeSeeder::class);
        $supervisor = $this->service->findByName('Employee 3');
        $supervisor1 = $this->service->findByName('Employee 1');

        // Create new employee
        $employee = $this->service->createOrUpdate([
            'name' => 'New Employee',
            'supervisor_id' => $supervisor->id,
        ]);

        $this->assertEquals('New Employee', $employee->name);
        $this->assertEquals('Employee 3', $employee->supervisor->name);
        $this->assertTrue($employee->subordinates->isEmpty());

        // Update employee
        $employee = $this->service->createOrUpdate([
            'name' => 'New Employee',
            'supervisor_id' => $supervisor1->id,
        ]);
        $this->assertEquals('Employee 1', $employee->supervisor->name);
    }

    public function testCreateOrUpdateEmployees(): void
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
        $employee = $this->service->findByName('Employee 3');

        $this->assertEquals('Employee 3', $employee->name);
        $this->assertEquals('Employee 4', $employee->supervisor->name);

        $subordinates = $employee->subordinates->pluck('name')->toArray();
        $this->assertContains('Employee 1', $subordinates);
        $this->assertContains('Employee 2', $subordinates);

        // Assert the database records
        $this->assertDatabaseCount('employees', 5);
    }

    public function testGetHierarchy() : void
    {
        $this->seed(EmployeeSeeder::class);

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

        $rootEmployee = $this->service->findByName('Employee 3');
        $hierarchy = $this->service->getHierarchy($rootEmployee);
        $this->assertEquals(
            [
                'Employee 3' => [
                    'Employee 2' => [],
                    'Employee 1' => [],
                ]
            ],
            $hierarchy
        );

        $rootEmployee = $this->service->findByName('Employee 1');
        $hierarchy = $this->service->getHierarchy($rootEmployee);
        $this->assertEquals(
            [
                'Employee 1' => []
            ],
            $hierarchy
        );
    }
}
