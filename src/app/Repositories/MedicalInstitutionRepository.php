<?php

namespace App\Repositories;

use App\Models\MedicalInstitution;
use App\Repositories\Interfaces\MedicalInstitutionRepositoryInterface;

class MedicalInstitutionRepository extends BaseRepository
{
    public function __construct(MedicalInstitution $model)
    {
        parent::__construct($model);
    }
}
