<?php

namespace App\Model;

use App\Core\Database\Model\BaseModel;
use App\Repository\PriorityRepository;

class Priority extends BaseModel
{
    protected string $table = 'priorities';
    protected string $primaryKey = 'priority_id';

    public int $priority_id;
    public string $priority_name;

    public static function name(int $id): string
    {
        return ((new PriorityRepository())->find($id))->priority_name;
    }
}