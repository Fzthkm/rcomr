<?php

namespace App\Repositories;

use App\Models\Application;
use App\Models\Specialist;
use App\Repositories\Interfaces\SpecialistRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SpecialistRepository extends BaseRepository
{
    public function __construct(Specialist $model)
    {
        parent::__construct($model);
    }

    /**
     * Возвращает список специалистов для индекса.
     * Тянем только нужные поля и минимальные поля у связей, чтобы не ловить жир.
     */
    public function all(array $columns = ['*']): Collection
    {
        // гарантируем, что id и name всегда попадут в выборку
        if ($columns !== ['*']) {
            $columns = array_values(array_unique(array_merge($columns, ['id', 'name'])));
        }

        return $this->model
            ->with([
                'specialization:id,name',
                'workplace:id,name',
            ])
            ->orderBy('name')
            ->get($columns);
        // TODO: При необходимости сузить $columns ещё сильнее под твой Blade.
    }

    /**
     * Лёгкий список для селекта (id, name).
     */
    public function forSelect(): Collection
    {
        return $this->model
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function getWorkplaceId(int $specialistId): ?int
    {
        return $this->find($specialistId)?->workplace_id;
    }

    /**
     * Специалист со списком заявок (минимальные поля у заявок).
     */
    public function findWithApplications(int $id): ?Specialist
    {
        return $this->model
            ->with([
                'applications:id,number,patient_name,status,scheduled_at,specialist_id'
            ])
            ->find($id);
    }

    /**
     * Быстрая проверка количества заявок через withCount.
     */
    public function getApplicationsCount(int $id): int
    {
        return (int) ($this->model
            ->whereKey($id)
            ->withCount('applications')
            ->value('applications_count') ?? 0);
    }

    /**
     * Backward-compat: чтобы не падало, если где-то ещё зовут destroy().
     */
    public function destroy(int $id): bool
    {
        return $this->delete($id);
    }

    /**
     * Перекидывание заявок с одного спеца на другого.
     */
    public function reassignApplications(int $fromId, int $toId, array $applicationIds): int
    {
        if (empty($applicationIds)) {
            return 0;
        }

        return Application::query()
            ->whereIn('id', $applicationIds)
            ->where('specialist_id', $fromId)
            ->update(['specialist_id' => $toId]);
    }
}
