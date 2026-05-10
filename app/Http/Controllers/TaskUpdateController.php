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
        $service->update($id, $request->validated());

        return redirect()->route('Taskshome');
    }
        catch (\Exception $e){
            return redirect ()->back()->with('error', 'Tarefa não encontrada.');
        }
    }
}
