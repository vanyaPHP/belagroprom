<?php

namespace App\Core\Database\Model;

use App\Core\Database\Connection;

class BaseModel
{
    protected string $table;
    protected string $primaryKey;
    protected array $default = [];

    public function save(): void
    {
        $propertyNames = $this->getPropertyNamesToFill();
        $values = [];
        foreach ($propertyNames as $propertyName)
        {
            $values[$propertyName] = $this->$propertyName;
        }

        $query = "INSERT INTO {$this->table}(". implode(',', $propertyNames) . ") VALUES(" . implode(',',
                array_map(function($propertyName){
                    return ":$propertyName";
                }, $propertyNames)) . ")";

        $connection = Connection::getConnection();
        $stmt = $connection->prepare($query);
        $stmt->execute($values);
    }

    public function update(): void
    {
        $connection = Connection::getConnection();
        $propertyNames = $this->getPropertyNamesToFill();
        $params = [$this->primaryKey => $this->{$this->primaryKey}];
        $subQuery = "";
        foreach ($propertyNames as $propertyName)
        {
            $subQuery .= "$propertyName = :$propertyName, ";
            $params[$propertyName] = $this->$propertyName;
        }
        $query = "UPDATE {$this->table} SET";
        $query .= " " . substr($subQuery, 0, strlen($subQuery) - 2);
        $query .= " WHERE {$this->primaryKey} = :{$this->primaryKey}";
        $stmt = $connection->prepare($query);
        $stmt->execute($params);
    }

    public function delete(): void
    {
        $connection = Connection::getConnection();
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :{$this->primaryKey}";
        $stmt = $connection->prepare($query);
        $stmt->execute([$this->primaryKey => $this->{$this->primaryKey}]);
    }

    private function getPropertyNamesToFill(): array
    {
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties();
        $propertyNames = [];
        $defaults = [];
        $primaryKey = "";
        $tableName = "";
        foreach ($properties as $property)
        {
            if ($property->getName() == "primaryKey")
            {
                $primaryKey = $property->getDefaultValue();
            }
            else if ($property->getName() == "default")
            {
                $defaults = $property->getDefaultValue();
            }
            else if ($property->getName() != "table")
            {
                $propertyNames []= $property->getName();
            }
        }

        unset($propertyNames[array_search($primaryKey, $propertyNames)]);
        foreach ($defaults as $default)
        {
            unset($propertyNames[array_search($default, $propertyNames)]);
        }

        return $propertyNames;
    }
}