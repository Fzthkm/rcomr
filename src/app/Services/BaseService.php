<?php

namespace App\Services;

use App\DTOs\Interfaces\BaseDtoInterface;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Services\Interfaces\BaseServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

abstract class BaseService implements BaseServiceInterface
{
    protected BaseRepositoryInterface $repository;

    /**
     * Базовый префикс для кэш-ключей.
     * У конкретного сервиса он свой (например "specialists").
     */
    protected string $cachePrefix;

    /**
     * Время жизни кэша (по умолчанию сутки).
     */
    protected int $cacheTtl = 86400; // 1 день

    public function __construct(
        BaseRepositoryInterface $repository,
        string $cachePrefix,
    )
    {
        $this->repository = $repository;
        $this->cachePrefix = $cachePrefix;
    }

    /**
     * Общий метод для генерации ключей.
     */
    protected function cacheKey(string $suffix = 'all'): string
    {
        return "{$this->cachePrefix}.{$suffix}";
    }

    /**
     * Получить все записи с кэшированием.
     */
    public function all(): mixed
    {
        return Cache::remember(
            $this->cacheKey(),
            $this->cacheTtl,
            fn () => $this->repository->all(),
        );
    }

    public function find(int $id): mixed
    {
        return $this->repository->find($id);
    }

    public function create(BaseDtoInterface $dto): mixed
    {
        $model = $this->repository->store($dto);
        $this->clearCache();
        return $model;
    }

    public function update(int $id, BaseDtoInterface $dto): mixed
    {
        $model = $this->repository->find($id);
        $updated = $this->repository->update($model, $dto);
        $this->clearCache();

        return $updated;
    }

    public function delete(int $id): void
    {
        $model = $this->repository->find($id);
        $this->repository->delete($model);
        $this->clearCache();
    }

    /**
     * Очистка кэша (по умолчанию all, но можно и более хитрые ключи).
     */
    public function clearCache(string $suffix = 'all'): void
    {
        Cache::forget($this->cacheKey($suffix));
    }
}
