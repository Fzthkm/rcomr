<?php

namespace App\Services\Interfaces;

use App\DTOs\Interfaces\BaseDtoInterface;

interface BaseServiceInterface
{
    public function all(): mixed;
    public function find(int $id): mixed;
    public function create(BaseDtoInterface $dto): mixed;
    public function update(int $id, BaseDtoInterface $dto): mixed;
    public function delete(int $id): void;
}

