<?php

namespace App\Services;

use App\Repositories\SpecialistRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SpecialistService extends BaseService
{
    protected string $cachePrefix = 'specialists';

    public function __construct(SpecialistRepository $repository)
    {
        parent::__construct($repository, 'specialists');
    }

    public function getWorkplaceId(int $specialistId): ?int
    {
        return $this->repository->getWorkplaceId($specialistId);
    }

    public function forSelect(): Collection
    {
        return Cache::remember(
            $this->cacheKey("forSelect"),
            86400,
            fn () => $this->repository->forSelect()
        );
    }

    public function forgetForSelectCache(): void
    {
        $this->clearCache("forSelect");
    }

    public function checkApplications(int $specialistId): bool
    {
        if ($this->repository->getApplicationsCount($specialistId) > 0) {
            return true;
        }

        return false;
    }
}
