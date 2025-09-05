<?php

namespace App\Services;

use App\Repositories\MedicalInstitutionRepository;

class MedicalInstitutionService extends BaseService
{
    public function __construct(MedicalInstitutionRepository $repository)
    {
        parent::__construct($repository, 'institutions');
    }
}
