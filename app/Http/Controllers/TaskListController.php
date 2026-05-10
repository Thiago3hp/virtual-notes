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
        $task = $service -> tasklist();
        return Inertia::render('TasksHome', [
            'task' => TaskResource::collection($task),
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

        $task = $service -> searchTaskName($name);

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

        $task = $service -> searchTaskStatus($status);

        return TaskResource::collection($task);
        }
        catch (\Exception $e){
            return redirect ()->back()->with('error', 'Tarefa não encontrada.');
        }
    }
}
