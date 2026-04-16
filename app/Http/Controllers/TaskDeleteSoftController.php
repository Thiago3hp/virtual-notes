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

        return response()->json(['message' => 'Tarefa deletada com sucesso.']);
        }
        catch(\Exception $e){
            return response()->json(['message' => 'Tarefa não encontrada.'], 404);
        } 
    }
}

