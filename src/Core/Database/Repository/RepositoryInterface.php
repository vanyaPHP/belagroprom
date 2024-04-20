<?php

namespace App\Core\Database\Repository;

interface RepositoryInterface
{
    public function findAll(array $conditions = [], array $orderBy = []): array;
    public function find(mixed $id): ?object;
}