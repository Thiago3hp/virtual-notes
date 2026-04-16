<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;


class TaskUpdateController extends Controller
{
    public function update($id, TaskRequest $request, TaskService $service)
    {
        try {
            $task = $service -> update($id, $request -> validated());
            return new TaskResource($task);
        } 
        catch (\Exception $e){
            return response()->json(['message' => 'Tarefa não encontrada.'], 404);
        }
    }
}
