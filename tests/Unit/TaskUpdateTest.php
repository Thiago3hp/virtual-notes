<?php

namespace Tests\Unit;

use App\Services\Taskservice;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class TaskUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_UpdateTask(): void
    {
        $service = new Taskservice();

        $user = User::factory()->create();

        $task = Task::create([
        'title' => 'Título inicial',
        'description' => 'Descrição inicial',
        'status' => 'pending',
        'user_id' => $user->id,
        ]);

        $data = [
            'title' => 'Novo título da Task',
            'description' => 'Nova descrição da task.',
            'status' => 'pending',
        ];

        $updatetask = $service->updateTask($task, $data);

        $this->assertNotNull($updatetask);

        $this->assertDatabaseHas('tasks', 
        [    
            'id' => $task->id,
            'title' => 'Novo título da Task',
            'description' => 'Nova descrição da task.',
            'status' => 'pending',
            'user_id' => $user->id,
        ]);
    }
}
