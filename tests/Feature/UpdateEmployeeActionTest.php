<?php

namespace Tests\Feature;

use App\Actions\UpdateEmployeeAction;
use App\Services\EmployeeService;
use Database\Seeders\EmployeeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateEmployeeActionTest extends TestCase
{
    use RefreshDatabase;
    protected UpdateEmployeeAction $action;
    protected EmployeeService $service;

    public function setup() : void
    {
        parent::setup();
        $this->action = app(UpdateEmployeeAction::class);
        $this->service = app(EmployeeService::class);
        $this->seed(EmployeeSeeder::class);
    }

    public function testGetEmployee()
    {
        $employee = $this->service->findByName('Employee 1');
        $newSupervisor = $this->service->findByName('Employee 2');

        $this->action->execute(
            $employee,
            [
                'name' => 'New Employee',
                'supervisor_id' => $newSupervisor->id,
            ]
        );
        $oldSupervisor = $this->service->findByName('Employee 3');

        $this->assertEquals('New Employee', $employee->name);
        $this->assertEquals('Employee 2', $employee->supervisor->name);
        $this->assertNotContains('Employee 1', $oldSupervisor->subordinates->pluck('name')->toArray());
    }
}
