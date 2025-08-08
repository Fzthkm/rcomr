<?php

namespace App\Repositories;

use App\Models\Specialist;
use App\Repositories\Interfaces\SpecialistRepositoryInterface;

class SpecialistRepository extends BaseRepository
{
    public function __construct(Specialist $model)
    {
        parent::__construct($model);
    }

    public function getWorkplaceId(int $specialistId): Int
    {
        return $this->find($specialistId)->get('workplace_id');
    }
}
