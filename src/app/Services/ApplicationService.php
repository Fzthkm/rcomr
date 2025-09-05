<?php

namespace App\Services;

use App\DTOs\ApplicationDTO;
use App\DTOs\Interfaces\BaseDtoInterface;
use App\Repositories\ApplicationRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ApplicationService extends BaseService
{
    public function __construct(
        ApplicationRepository $repository,
        private SpecialistService $specialistService,
    ) {
        parent::__construct($repository, 'applications');
    }

    public function allWithPagination(array $filters = [], int $perPage = 100): LengthAwarePaginator
    {
        $query = $this->repository->queryWithRelations();

        return $query->paginate($perPage);
    }

    public function create(BaseDtoInterface $dto): mixed
    {
        if ($dto->specialistId) {
            $dto->fromInstitutionId = $this->specialistService->getWorkplaceId($dto->specialistId);
        }
        return $this->repository->store($dto->withApplicationNumber($this->generateApplicationNumber()));
    }

    public function generateApplicationNumber(): Int
    {
        $latestApplication = $this->repository->latestApplication(now()->year);

        return $latestApplication ? $latestApplication->application_number + 1 : 1;
    }

}
