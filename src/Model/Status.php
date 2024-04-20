<?php

namespace App\Model;

use App\Core\Database\Model\BaseModel;
use App\Repository\StatusRepository;

class Status extends BaseModel
{
    protected string $table = 'statuses';
    protected string $primaryKey = 'status_id';

    public int $status_id;
    public string $status_name;

    public static function name(int $id): string
    {
        return ((new StatusRepository())->find($id))->status_name;
    }
}