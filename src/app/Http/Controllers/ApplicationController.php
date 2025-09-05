<?php

namespace App\Http\Controllers;

use App\DTOs\ApplicationUpdateDTO;
use App\DTOs\ApplicationDTO;
use App\Http\Requests\ApplicationCreateRequest;
use App\Http\Requests\ApplicationUpdateRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\Specialist;
use App\Services\ApplicationService;
use App\Services\Diagnosis\DiagnosisService;
use App\Services\MedicalInstitutionService;
use App\Services\SpecialistService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationController
{
    public function __construct(
        public readonly ApplicationService $service,
        public readonly MedicalInstitutionService $institutionService,
        public readonly SpecialistService $specialistService,
        public readonly DiagnosisService $diagnosisService,
    ) {
    }

    public function index(Request $request): View
    {
        $perPage = max(1, min(100, (int) $request->get('per_page', 20))); // чуть гигиены
        $paginated = $this->service->allWithPagination([], $perPage);

        // Если нужна проекция через ресурс — не ломаем пагинатор
        $paginated->setCollection(
            $paginated->getCollection()->map(
                fn ($m) => (new ApplicationResource($m))->toArray($request)
            )
        );

        return view('applications.index', [
            'paginated' => $paginated,
        ]);
    }


    public function create(Request $request): View
    {
        $prefill = [];

        if ($fromId = $request->integer('from')) {
            if ($src = $this->service->find($fromId)) {
                $prefill = [
                    'application_number'  => $src->application_number,
                    'consultation_date'   => optional($src->consultation_date)->format('d.m.Y'),
                    'institution_id'      => $src->institution_id,
                    'specialist_id'       => $src->specialist_id,
                    'patient_name'        => $src->patient_name,
                    'patient_birth_year'  => $src->patient_birth_year,
                    'diagnosis_id'        => $src->diagnosis_id,
                ];
            }
        }

        $reqFill = $request->only([
            'application_number',
            'consultation_date',
            'institution_id',
            'specialist_id',
            'patient_name',
            'patient_birth_year',
            'diagnosis_id',
        ]);

        // убираем пустяки и мержим
        $prefill = array_filter($prefill, fn($v) => $v !== null && $v !== '');
        $reqFill = array_filter($reqFill, fn($v) => $v !== null && $v !== '');
        $prefill = array_merge($prefill, $reqFill);

        return view('applications.create', [
            'prefill'      => $prefill,
            'institutions' => $this->institutionService->all(),
            'specialists'  => $this->specialistService->forSelect(),
            'diagnoses'    => $this->diagnosisService->all(),
        ]);
    }

    public function edit(int $id): View
    {
        $application = $this->service->find($id);

        return view('applications.edit', [
            'application' => $application,
            'institutions' => $this->institutionService->all(),
            'specialists'  => $this->specialistService->forSelect(),
            'diagnoses'    => $this->diagnosisService->all(),
        ]);
    }

    public function update(ApplicationUpdateRequest $request, int $id)
    {
        $dto = ApplicationUpdateDTO::fromRequest($request);

        $this->service->update($id, $dto);

        return redirect()->route('applications.index')
            ->with('success', 'Заявка обновлена');
    }

    public function store(ApplicationCreateRequest $request): RedirectResponse
    {
        $dto = ApplicationDTO::fromRequest($request);

        $this->service->create($dto);

        return redirect()->route('applications.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('applications.index');
    }

    public function bySpecialist(int $specialist)
    {
        $spec = Specialist::with('specialization:id,name')->findOrFail($specialist);

        $apps = Application::query()
            ->with(['specialist:id,name', 'requestedByInstitution:id,name', 'referredFromInstitution:id,name'])
            ->where('specialist_id', $specialist)
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('applications.by-specialist', compact('spec', 'apps'));
    }
}
