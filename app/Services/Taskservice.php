<?php

namespace App\Services;

use App\Models\Task;

class Taskservice
{
    public function createTask(array $dados)
    {
        // if (isset($dados['user_id'])) {
        //     $dados['user_id'] = $dados['user_id'];
        // } else {
        //     $dados['user_id'] = null;
        // }

        return Task::create($dados);
    }

    public function updateTask(Task $task, array $dados)
    {   
        $task->update($dados);
        return $task;
    }

    public function searchTaskName(string $name)
    {
        if (isset($name)) {
            $name = $name;
        } else {
            $name = null;
        }

        return Task::where('title', 'LIKE', "%$name%")->get();
    }

    public function searchTaskStatus(string $status)
    {
        if (isset($status)) {
            $status = $status;
        } else {
            $status = null;
        }

        return Task::where('status', 'status', "%$status%")->get();
    }

    public function searchTaskDeleted(bool $deleted)
    {
        if (isset($deleted)) {
            $deleted = $deleted;
        } else {
            $deleted = null;
        }

        return Task::where('deleted', 'LIKE', "%$deleted%")->get();
    }

    public function deleteTask(Task $task)
    {
        if (isset($task)) {
            $task = $task;
        } else {
            $task = null;
        }
        $task->softDelete();
    }

    public function restoreTask(Task $task)
    {   
        if (isset($task)) {
            $task = $task;
        } else {
            $task = null;
        }
        $task->restore();
    }

    public function listar()
    {
        return Task::all();
    }
}
