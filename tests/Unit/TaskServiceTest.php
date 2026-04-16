<?php

namespace Tests\Unit;
use App\Services\Taskservice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class TaskServiceTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_create_task(): void
    {
        $service = new Taskservice();
        /*Cenario*/
        $user = User::factory()->create();

        $data = [
            'title' => 'Título da Task',
            'description' => 'descrição da task.',
            'status' => 'pending',
            'user_id' => $user->id,
        ];

        /*Ação*/
        $task = $service->createTask($data);

        /*Verificação*/
        $this->assertNotNull($task);

        $this->assertDatabaseHas('tasks', 
        [
            'title' => 'Título da Task',
            'description' => 'descrição da task.',
            'status' => 'pending',
            'user_id' => $user->id,
        ]);
        
    }
}
