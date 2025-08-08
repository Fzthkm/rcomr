<?php

namespace App\Repositories;

use App\Models\Application;
use Illuminate\Database\Eloquent\Builder;

class ApplicationRepository extends BaseRepository
{
    /**
     * @param Application $model
     */
    public function __construct(Application $model)
    {
        parent::__construct($model);
    }

    public function queryWithRelations(): Builder
    {
        return $this->model->with([
            'requestedByInstitution',
            'referredFromInstitution',
            'specialist',
            'diagnosis',
        ])->orderByDesc('created_at');
    }

    public function latestApplication(int $currentYear)
    {
        return $this->model::whereYear('created_at', $currentYear)
            ->orderByDesc('application_number')
            ->first();
    }
}
