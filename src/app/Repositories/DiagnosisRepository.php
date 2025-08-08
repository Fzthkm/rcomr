<?php

namespace App\Repositories;

use App\Models\Diagnosis;
use App\Repositories\Interfaces\DiagnosisRepositoryInterface;

class DiagnosisRepository extends BaseRepository
{
    public function __construct(Diagnosis $model)
    {
        parent::__construct($model);
    }
}
