<?php

namespace App\Http\Controllers;

use App\DTOs\ApplicationDTO;
use App\Http\Requests\ApplicationCreateRequest;
use App\Http\Resources\ApplicationResource;
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
        public ApplicationService $service,
        public MedicalInstitutionService $institutionService,
        public SpecialistService $specialistService,
        public DiagnosisService $diagnosisService,
    ) {
    }

    public function index(Request $request): View
    {
        $perPage = $request->get('per_page', 20);
        $paginated = $this->service->allWithPagination([], $perPage);
        $applications = ApplicationResource::collection($paginated)->resolve();

        return view('applications.index', [
            'applications' => $applications,
            'pagination' => $paginated->links(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('applications.create', [
            'prefill' => $request->only([
                'application_number',
                'consultation_date',
                'institution_id',
                'specialist_id',
                'patient_name',
                'patient_year',
                'diagnosis_id',
            ]),
            'institutions' => $this->institutionService->all(),
            'specialists' => $this->specialistService->all(),
            'diagnoses' => $this->diagnosisService->all(),
        ]);
    }

    public function update(ApplicationUpdateRequest $request): RedirectResponse
    {


        return redirect()->route('applications.index');
    }

    public function store(ApplicationCreateRequest $request): RedirectResponse
    {
        $dto = ApplicationDTO::fromRequest($request);

        $this->service->create($dto);

        return redirect()->route('applications.index');
    }

    protected function getViewPath(string $action): string
    {
        return "applications.$action";
    }

    protected function getRouteBase(): string
    {
        return 'applications';
    }

//    protected function buildDtoFromRequest(Request $request): ApplicationDTO
//    {
//        return ApplicationDTO::fromRequest($request);
//    }
}
