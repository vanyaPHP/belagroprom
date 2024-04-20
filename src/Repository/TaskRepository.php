<?php

namespace App\Repository;

use App\Core\Database\Repository\BaseRepository;
use App\Model\Task;

class TaskRepository extends BaseRepository
{
    protected string $model = Task::class;
}