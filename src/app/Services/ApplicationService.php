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
        parent::__construct($repository);
    }

    public function allWithPagination(array $filters = [], int $perPage = 100): LengthAwarePaginator
    {
        $query = $this->repository->queryWithRelations();

//        if (!empty($filters['status'])) {
//            $query->where('status', $filters['status']);
//        }
//
//        if (!empty($filters['application_number'])) {
//            $query->where('application_number', $filters['application_number']);
//        }
//

        return $query->paginate($perPage);
    }

    public function create(BaseDtoInterface $dto): mixed
    {
        $dto->fromInstitutionId = $this->specialistService->create($dto);
        return $this->repository->store($dto->withApplicationNumber($this->generateApplicationNumber()));
    }

    public function generateApplicationNumber(): int
    {
        $latestApplication = $this->repository->latestApplication(now()->year);

        return $latestApplication ? $latestApplication->application_number + 1 : 1;
    }

}
