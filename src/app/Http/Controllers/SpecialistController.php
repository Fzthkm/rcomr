<?php

namespace App\Http\Controllers;

use App\DTOs\SpecialistDTO;
use App\Http\Requests\SpecialistCreateRequest;
use App\Models\Specialist;
use App\Services\MedicalInstitutionService;
use App\Services\SpecialistService;
use App\Services\SpecializationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SpecialistController extends Controller
{
    public function __construct(
        private SpecialistService $specialistService,
        private MedicalInstitutionService $medicalInstitutionService,
        private SpecializationService $specializationService,
    ) {}

    /** Список специалистов */
    public function index(Request $request)
    {
        // Для селекта в модалке: /specialists?format=select
        if ($request->query('format') === 'select') {
            // Лучше отдать компактный список прямо из сервиса/репозитория
            $items = $this->specialistService->forSelect() // Collection with id,name
            ->map(fn ($s) => ['value' => $s->id, 'text' => $s->name])
                ->values();

            return response()->json($items, Response::HTTP_OK);
        }

        // Основной список (желательно с with('specialization') в сервисе/репозитории)
        $specialists = $this->specialistService->all(); // сделай eager load внутри
        return view('specialists.index', compact('specialists'));
    }

    /** Форма создания */
    public function create()
    {
        return view('specialists.create', [
            'workplaces'    => $this->medicalInstitutionService->all(),
            'specializations' => $this->specializationService->all(),
        ]);
    }

    /** Создание */
    public function store(SpecialistCreateRequest $request)
    {
        $dto = SpecialistDTO::fromRequest($request);
        $this->specialistService->create($dto);
        $this->specialistService->clearCache();
        $this->specialistService->clearCache('forSelect');

        return redirect()->route('specialists.index');
    }

    /** Пока пустые CRUD-действия держим с корректными типами */
    public function show(int $id)
    {

    }
    public function edit(int $id)
    {
        $specialist = Specialist::findOrFail($id);
        $workplaces = $this->medicalInstitutionService->all();
        $specializations = $this->specializationService->all();

        return view('specialists.edit', compact('specialist', 'workplaces', 'specializations'));
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name'               => ['required','string','max:255'],
            'workplace_id'       => ['required','integer','exists:medical_institutions,id'],
            'specialization_id'  => ['required','integer','exists:specializations,id'],
            'education'          => ['nullable','string','max:255'],
            'additional_info'    => ['nullable','string','max:1000'],
        ]);

        $specialist = Specialist::findOrFail($id);
        $specialist->update($data);

        $this->specialistService->clearCache();
        $this->specialistService->clearCache('forSelect');

        return redirect()->route('specialists.index')->with('success', 'Изменения сохранены.');
    }

    public function destroy(int $id)
    {
        $hasApplications = $this->specialistService->checkApplications($id);

        if ($hasApplications) {
            return redirect()->route('applications.by-specialist', $id);
        }

        $this->specialistService->delete($id);
        $this->specialistService->clearCache();
        $this->specialistService->clearCache("forSelect");
        return redirect()->route('specialists.index');

        // 1) Проверка: есть ли заявки
        try {
            $check = $this->specialistService->deletionCheck($id);
        } catch (ModelNotFoundException) {
            return redirect()
                ->route('specialists.index')
                ->with('err', 'Специалист не найден');
        }

        if (!$check['deletable']) {
            // 2) Есть хвосты — идём смотреть/разруливать заявки этого спеца
            return redirect()
                ->route('applications.by-specialist', $id)
                ->with('err', "Нельзя удалить: заявок {$check['count']}. Сначала разрули их.");
        }

        // 3) Хвостов нет — удаляем
        $ok = $this->specialistService->destroySafe($id);

        return redirect()
            ->route('specialists.index')
            ->with($ok ? 'ok' : 'err', $ok ? 'Специалист удалён' : 'Удаление не удалось');
    }
}
