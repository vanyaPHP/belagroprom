<?php

namespace App\Core\Database\Repository;

use App\Core\Database\Connection;

class BaseRepository implements RepositoryInterface
{
    protected string $model;

    public function findAll(array $conditions = [], array $orderBy = []): array
    {
        $reflection = new \ReflectionClass($this->model);
        $properties = $reflection->getProperties();
        $propertyNames = [];
        $tableName = '';
        foreach ($properties as $property)
        {
            if ($property->getName() == "table")
            {
                $tableName = $property->getDefaultValue();
            }
            else if ($property->getName() != "primaryKey" && $property->getName() != "default")
            {
                $propertyNames []= $property->getName();
            }
        }

        $connection = Connection::getConnection();
        $query = "SELECT * FROM {$tableName}";
        $conditionValues = [];
        if (count($conditions) != 0)
        {
            $query .= " WHERE ";
            foreach ($conditions as $field => $conditionInfo)
            {
                $fieldName = $field;
                if (array_key_exists('sign', $conditionInfo))
                {
                    $sign = $conditionInfo['sign'];
                    $value = $conditionInfo['value'];

                    $conditionValues[$fieldName] = $value;
                    $query .= "$fieldName $sign :$fieldName AND ";
                }
                else
                {
                    foreach ($conditionInfo as $key => $subConditionInfo)
                    {
                        $sign = $subConditionInfo['sign'];
                        $value = $subConditionInfo['value'];
                        $conditionValues[$fieldName . $key] = $value;
                        $query .= "$fieldName $sign :" . $fieldName.$key . " AND ";
                    }
                }
            }

            $query = substr($query, 0, strlen($query) - 5);
        }
        if (count($orderBy) > 0)
        {
            $query .= " ORDER BY ";
            foreach ($orderBy as $fieldName => $direction)
            {
                $query .= "$fieldName $direction, ";
            }

            $query = substr($query, 0, strlen($query) - 2);
        }

        $stmt = $connection->prepare($query);
        $stmt->execute($conditionValues);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $data = [];
        foreach ($result as $record)
        {
            $instance = new $this->model;
            foreach ($propertyNames as $propertyName)
            {
                $instance->$propertyName = $record[$propertyName];
            }

            $data []= $instance;
        }

        return $data;
    }

    public function find(mixed $id): ?object
    {
        $reflection = new \ReflectionClass($this->model);
        $properties = $reflection->getProperties();
        $propertyNames = [];
        $tableName = '';
        $primaryKey = '';
        foreach ($properties as $property)
        {
            if ($property->getName() == "table")
            {
                $tableName = $property->getDefaultValue();
            }
            else if ($property->getName() == "primaryKey")
            {
                $primaryKey = $property->getDefaultValue();
            }
            else if ($property->getName() != "default")
            {
                $propertyNames []= $property->getName();
            }
        }

        $connection = Connection::getConnection();
        $query = "SELECT * FROM $tableName WHERE $primaryKey = :$primaryKey";
        $stmt = $connection->prepare($query);
        $stmt->execute([$primaryKey => $id]);

        $record = $stmt->fetch(\PDO::FETCH_ASSOC);
        $instance = new $this->model;
        foreach ($propertyNames as $propertyName)
        {
            $instance->$propertyName = $record[$propertyName];
        }

        return $instance;
    }
}