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
        $task = $service -> createTask($request -> validated());
        }
        catch (\Exception $e){
           dd($e->getMessage());
        }

        return redirect ()->back()->with('success', 'Tarefa criada com sucesso!');
    }
    
}
