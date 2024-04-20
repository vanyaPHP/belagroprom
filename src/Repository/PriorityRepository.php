<?php

namespace App\Repository;

use App\Core\Database\Repository\BaseRepository;
use App\Model\Priority;

class PriorityRepository extends BaseRepository
{
    protected string $model = Priority::class;
}