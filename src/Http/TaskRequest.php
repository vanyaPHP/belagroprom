<?php

namespace App\Http;

use App\Core\Http\Request;

class TaskRequest extends Request
{
    public function handle(): array
    {
        $conditions = [];
        $status_id = $this->query('status_id');
        $priority_id = $this->query('priority_id');
        $createdFrom = $this->query('created_from');
        $createdTo = $this->query('created_to');
        if ($status_id != null)
        {
            $conditions['status_id'] = [
                'sign' => '=',
                'value' => $status_id
            ];
        }
        if ($priority_id != null)
        {
            $conditions['priority_id'] = [
                'sign' => '=',
                'value' => $priority_id
            ];
        }
        if ($createdFrom != null)
        {
            $conditions['created_at'] []= [
                'sign' => '>=',
                'value' => $createdFrom . " 00:00:00"
            ];
        }
        if ($createdTo != null)
        {
            $conditions['created_at'] []= [
                'sign' => '<=',
                'value' => $createdTo . " 23:59:59"
            ];
        }

        return $conditions;
    }
}