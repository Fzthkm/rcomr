<?php

namespace App\DTOs;

use App\DTOs\Interfaces\BaseDtoInterface;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationDTO implements BaseDtoInterface
{
    public function __construct(
        public string $consultationDate,
        public int $institutionId,
        public int $specialistId,
        public string $patientName,
        public string $patientYear,
        public int $diagnosisId,
        public ?int $institutionFromId = null,
        public ?int $applicationNumber = null,
        public ?string $createdAt = null,
    ) {
    }

    public static function fromRequest(FormRequest $request): static
    {
        return new self(
            consultationDate: $request->input('consultation_date'),
            institutionId: $request->input('institution_id'),
            specialistId: $request->input('specialist_id'),
            patientName: $request->input('patient_name'),
            patientYear: $request->input('patient_year'),
            diagnosisId: $request->input('diagnosis_id'),
        );
    }

    public function withApplicationNumber(int $number): static
    {
        $this->applicationNumber = $number;
        return $this;
    }

    public function withCreatedAt(string $date): static
    {
        $this->createdAt = $date;
        return $this;
    }

    public function withInstitutionFromId(int $id): static
    {
        $this->institutionFromId = $id;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'application_number' => $this->applicationNumber,
            'consultation_date' => $this->consultationDate,
            'institution_id' => $this->institutionId,
            'specialist_id' => $this->specialistId,
            'patient_name' => $this->patientName,
            'patient_year' => $this->patientYear,
            'diagnosis_id' => $this->diagnosisId,
            'created_at' => $this->createdAt,
        ];
    }
}
