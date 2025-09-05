<?php

namespace App\Repositories\Interfaces;

use App\DTOs\Interfaces\BaseDtoInterface;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function all(): mixed;

    public function find(int $id): ?Model;

    public function store(BaseDtoInterface $dto): Model;

    public function update(Model $model, BaseDtoInterface $dto): Model;

    public function delete(int|Model $target): bool;
}
