<?php

namespace Tests\Feature;

use App\Actions\GetEmployeeHierarchyFromLeafAction;
use App\Services\EmployeeService;
use Database\Seeders\EmployeeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetEmployeeHierarchyFromLeafActionTest extends TestCase
{
    use RefreshDatabase;
    protected GetEmployeeHierarchyFromLeafAction $action;
    protected EmployeeService $service;

    public function setup() : void
    {
        parent::setup();
        $this->action = app(GetEmployeeHierarchyFromLeafAction::class);
        $this->service = app(EmployeeService::class);
        $this->seed(EmployeeSeeder::class);
    }

    public function testGetHierarchyByEmployee()
    {
        $employee = $this->service->findByName('Employee 3');
        $hierarchy = $this->action->execute($employee, 2);

        $this->assertEquals([
                'Employee 5' => [
                    'Employee 4' => [
                        'Employee 3' => []
                    ]
                ]
            ],
            $hierarchy
        );

        $hierarchy = $this->action->execute($employee);
        $this->assertEquals([
                'Employee 5' => [
                    'Employee 4' => [
                        'Employee 3' => []
                    ]
                ]
            ],
            $hierarchy
        );

        $hierarchy = $this->action->execute($employee, 1);
        $this->assertEquals([
                'Employee 4' => [
                    'Employee 3' => []
                ]
            ],
            $hierarchy
        );

        $hierarchy = $this->action->execute($employee, -1);
        $this->assertEquals([
            'Employee 3' => []
            ],
            $hierarchy
        );
    }
}
