<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;


class TaskListController extends Controller
{
    public function index(TaskService $service)
    {
        $tasks = $service -> list();
        return TaskResource::collection($tasks);
    }

    public function showList ($id,TaskRequest $request, TaskService $service)
    {
        try {
            $task = $service -> find($id);
            return new TaskResource($task);
        } 
        catch (\Exception $e){
            return response()->json(['message' => 'Tarefa não encontrada.'], 404);
        }

    }

    public function searchbyName(TaskRequest $request, TaskService $service)
    {
        try{
        $name = $request->input('name');

        $tasks = $service -> searchTaskName($name);

        return TaskResource::collection($tasks);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'Tarefa não encontrada.'], 404);
        }
    }

    public function searchbyStatus(TaskRequest $request, TaskService $service)
    {
        try{
        $status = $request->input('status');

        $tasks = $service -> searchTaskStatus($status);

        return TaskResource::collection($tasks);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'Tarefa não encontrada.'], 404);
        }
    }
}
