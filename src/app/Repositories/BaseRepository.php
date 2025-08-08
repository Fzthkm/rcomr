<?php

namespace App\Repositories;

use App\DTOs\Interfaces\BaseDtoInterface;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BaseRepository implements BaseRepositoryInterface
{
    public function __construct(protected Model $model)
    {
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    public function find(int $id, array $columns = ['*']): ?Model
    {
        return $this->model->find($id, $columns);
    }

    public function store(BaseDtoInterface $dto): Model
    {
        return $this->model::create($dto->toArray());
    }

    public function update(Model $model, BaseDtoInterface $dto): Model
    {
        $model->update($dto->toArray());
        return $model;
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }
}
