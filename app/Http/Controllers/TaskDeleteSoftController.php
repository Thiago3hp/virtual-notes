<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Services\TaskService;

class TaskDeleteSoftController extends Controller
{
    public function destroy($id,TaskRequest $request, TaskService $service)
    {
       try{
            $service -> deleteTask($id);

        return redirect ()->back()->with('success', 'Tarefa deletada com sucesso!');
        }
        catch(\Exception $e){
            return redirect ()->back()->with('error', 'Tarefa não encontrada.');
        } 
    }
}

