<?php

namespace App\Repository;

use App\Core\Database\Repository\BaseRepository;
use App\Model\Status;

class StatusRepository extends BaseRepository
{
    protected string $model = Status::class;
}