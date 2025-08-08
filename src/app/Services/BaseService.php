<?php

namespace App\Services;

use App\DTOs\Interfaces\BaseDtoInterface;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Services\Interfaces\BaseServiceInterface;

class BaseService implements BaseServiceInterface
{
    protected BaseRepositoryInterface $repository;

    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all(): mixed
    {
        return $this->repository->all();
    }

    public function find(int $id): mixed
    {
        return $this->repository->find($id);
    }

    public function create(BaseDtoInterface $dto): mixed
    {
        return $this->repository->store($dto);
    }

    public function update(int $id, BaseDtoInterface $dto): mixed
    {
        $model = $this->repository->find($id);
        return $this->repository->update($model, $dto);
    }

    public function delete(int $id): void
    {
        $model = $this->repository->find($id);
        $this->repository->delete($model);
    }
}
