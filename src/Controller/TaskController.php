<?php

namespace App\Controller;

use App\Core\Http\Controller;
use App\Http\TaskRequest;
use App\Model\Task;
use App\Repository\PriorityRepository;
use App\Repository\StatusRepository;
use App\Repository\TaskRepository;

class TaskController extends Controller
{
    public function index(TaskRequest $taskRequest): void
    {
        $priorities = (new PriorityRepository())->findAll();
        $statuses = (new StatusRepository())->findAll();
        $conditions = $taskRequest->handle();
        $error = $this->request->query('error');
        $status_id = isset($conditions['status_id']) ? $conditions['status_id']['value'] : 0;
        $priority_id = isset($conditions['priority_id']) ? $conditions['priority_id']['value'] : 0;
        $created_from = null;
        $created_to = null;
        $createdAtConditions = $conditions['created_at'];
        if ($createdAtConditions != null)
        {
            if (count($createdAtConditions) == 2)
            {
                $created_from = $createdAtConditions[0]['value'];
                $created_to = $createdAtConditions[1]['value'];
            }
            else
            {
                $created_from = ($createdAtConditions[0]['sign'] == ">=") ? $createdAtConditions[0]['value'] : null;
                $created_to = ($createdAtConditions[0]['sign'] == "<=") ? $createdAtConditions[0]['value']: null;
            }
        }

        $tasks = (new TaskRepository())->findAll($conditions, ['created_at' => 'DESC']);

        include dirname(__DIR__, 2) . '/resources/html/tasks.html';
    }

    public function store(): void
    {
        $body = $this->request->body();
        if ($body['task_description'] == null)
        {
            header('Location: http://localhost:8000?error=' . urlencode("Заполните поле описания задачи"));
        }
        $task = new Task();
        $task->status_id = $body['status_id'];
        $task->task_description = $body['task_description'];
        $task->priority_id = $body['priority_id'];
        $task->save();

        header('Location: http://localhost:8000/');
    }

    public function edit(): void
    {
        $task = (new TaskRepository())->find($this->request->body('task_id'));
        $priorities = (new PriorityRepository())->findAll();
        $statuses = (new StatusRepository())->findAll();
        include dirname(__DIR__, 2) . '/resources/html/edit_task.html';
    }

    public function update(): void
    {
        $body = $this->request->body();
        /**
         * @var Task $task
         */
        $task = (new TaskRepository())->find($body['task_id']);
        $task->task_description = $body['task_description'];
        $task->status_id = $body['status_id'];
        $task->priority_id = $body['priority_id'];
        $task->update();

        header('Location: http://localhost:8000/');
    }

    public function delete(): void
    {
        /**
         * @var Task $task
         */
        $task = (new TaskRepository())->find($this->request->body('task_id'));
        $task->delete();
        header('Location: http://localhost:8000/');
    }
}