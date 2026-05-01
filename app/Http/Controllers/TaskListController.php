<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use inertia\Inertia;


class TaskListController extends Controller
{
    public function showList(TaskService $service)
    {
        $tasks = $service -> tasklist();
        return Inertia::render('TasksHome', [
            'tasks' => TaskResource::collection($tasks),
    ]);
    }

    public function showListId ($id,TaskRequest $request, TaskService $service)
    {
        try {
            $task = $service -> find($id);
            return new TaskResource($task);
        } 
        catch (\Exception $e){
           return redirect ()->back()->with('error', 'Tarefa não encontrada.');
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
            return redirect ()->back()->with('error', 'Tarefa não encontrada.');
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
            return redirect ()->back()->with('error', 'Tarefa não encontrada.');
        }
    }
}
