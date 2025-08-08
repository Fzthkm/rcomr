<?php

namespace App\Services;

use App\Repositories\SpecialistRepository;

class SpecialistService extends BaseService
{
    public function __construct(SpecialistRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getWorkplaceId(int $specialistId): Int
    {
        return $this->repository->getWorkplaceId($specialistId);
    }
}
