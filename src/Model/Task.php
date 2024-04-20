<?php

namespace App\Model;

use App\Core\Database\Model\BaseModel;

class Task extends BaseModel
{
    protected string $table = 'tasks';
    protected string $primaryKey = 'task_id';
    protected array $default = [
        'created_at',
        'updated_at'
    ];

    public int $task_id;
    public string $task_description;
    public int $status_id;
    public int $priority_id;
    public string $created_at;
    public string $updated_at;
}