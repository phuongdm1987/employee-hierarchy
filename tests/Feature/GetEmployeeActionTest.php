<?php

namespace Tests\Feature;

use App\Actions\GetEmployeeAction;
use Database\Seeders\EmployeeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetEmployeeActionTest extends TestCase
{
    use RefreshDatabase;
    protected GetEmployeeAction $action;

    public function setup() : void
    {
        parent::setup();
        $this->action = app(GetEmployeeAction::class);
        $this->seed(EmployeeSeeder::class);
    }

    public function testGetEmployee()
    {
        $employee = $this->action->execute(['name' => 'Employee 3']);
        $this->assertEquals('Employee 3', $employee->name);

        $employee = $this->action->execute(['name' => 'Not Exist Employee']);
        $this->assertNull($employee);
    }
}
