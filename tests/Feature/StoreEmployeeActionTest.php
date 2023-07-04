<?php

namespace Tests\Feature;

use App\Actions\StoreEmployeeAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreEmployeeActionTest extends TestCase
{
    use RefreshDatabase;
    protected StoreEmployeeAction $action;

    public function setup() : void
    {
        parent::setup();
        $this->action = app(StoreEmployeeAction::class);
    }

    public function testGetEmployee()
    {
        $supervisor = $this->action->execute([
            'name' => 'Employee 3',
        ]);
        $this->assertEquals('Employee 3', $supervisor->name);

        $employee = $this->action->execute([
            'name' => 'Employee 2',
            'supervisor_id' => $supervisor->id,
        ]);
        $this->assertEquals('Employee 2', $employee->name);
        $this->assertEquals('Employee 3', $employee->supervisor->name);
    }
}
