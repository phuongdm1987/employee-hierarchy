<?php

namespace Tests\Feature;

use App\Actions\GetEmployeeHierarchyAction;
use App\Services\EmployeeService;
use Database\Seeders\EmployeeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetEmployeeHierarchyActionTest extends TestCase
{
    use RefreshDatabase;
    protected GetEmployeeHierarchyAction $action;
    protected EmployeeService $service;

    public function setup() : void
    {
        parent::setup();
        $this->action = app(GetEmployeeHierarchyAction::class);
        $this->service = app(EmployeeService::class);
        $this->seed(EmployeeSeeder::class);
    }

    public function testGetHierarchyByEmployee()
    {
        $employee = $this->service->findByName('Employee 5');
        $hierarchy = $this->action->execute($employee);

        $this->assertEquals([
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

        $employee = $this->service->findByName('Employee 3');
        $hierarchy = $this->action->execute($employee);
        $this->assertEquals([
                'Employee 3' => [
                    'Employee 2' => [],
                    'Employee 1' => [],
                ]
            ],
            $hierarchy
        );

        $employee = $this->service->findByName('Employee 2');
        $hierarchy = $this->action->execute($employee);
        $this->assertEquals([
                'Employee 2' => []
            ],
            $hierarchy
        );
    }
}
