<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;    
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;

class TaskCreateController extends Controller
{
    public function store(TaskRequest $request, TaskService $service)
    {   
        try {
        $task = $service -> create($request -> validated());
        }
        catch (\Exception $e){
            return response()->json(['message' => 'Erro ao criar tarefa.'], 500);
        }

        return new TaskResource($task);
    }
}
