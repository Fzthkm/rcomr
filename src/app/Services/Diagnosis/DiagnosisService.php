<?php

namespace App\Services\Diagnosis;

use App\Repositories\DiagnosisRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class DiagnosisService extends BaseService
{
    public function __construct(DiagnosisRepository $repository)
    {
        parent::__construct($repository);
    }

    public function all(): Collection
    {
        return Cache::rememberForever('diagnoses_list', function () {
            return $this->repository->all();
        });
    }
}
