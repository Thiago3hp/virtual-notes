<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskListController extends Controller
{
    public function index(TaskService $service)
    {
        $tasks = $service -> list();
        return TaskResource::collection($tasks);
    }

    public function showList    ($id, TaskService $service)
    {
        try {
            $task = $service -> find($id);
            return new TaskResource($task);
        } 
        catch (\Exception $e){
            return response()->json(['message' => 'Tarefa não encontrada.'], 404);
        }

    }

    public function searchbyName($name, TaskService $service)
    {
        $name = request ->input('name');

        $tasks = $service -> searchTaskName($name);

        return TaskResource::collection($tasks);
    }
}
