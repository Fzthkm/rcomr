<?php
// app/DTO/ApplicationUpdateDTO.php
namespace App\DTOs;

use App\DTOs\Concerns\CastsApplicationFields;
use App\DTOs\Interfaces\BaseDtoInterface;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * Частичный апдейт: хранит только реально присланные поля.
 */
class ApplicationUpdateDTO implements BaseDtoInterface
{
    use CastsApplicationFields;

    /** @var array<string,mixed> */
    private array $data = [];
    /** @var array<string,bool> */
    private array $present = [];

    public function __construct(
        public int $applicationNumber,
        public string $consultationDate,
        public int $institutionId,
        public int $fromInstitutionId,
        public int $specialistId,
        public ?string $patientName,
        public ?string $patientBirthYear,
        public int $diagnosisId,
        public string $createdAt,
    ) {
    }

    public static function fromRequest(FormRequest $request): static
    {
        return new self(
            applicationNumber: $request->input('application_number'),
            consultationDate: $request->input('consultation_date'),
            institutionId: $request->input('institution_id'),
            fromInstitutionId: $request->input('from_institution_id'),
            specialistId: $request->input('specialist_id'),
            patientName: $request->input('patient_name'),
            patientBirthYear: $request->input('patient_birth_year'),
            diagnosisId: $request->input('diagnosis_id'),
            createdAt: $request->input('created_at'),

        );
    }

    public function has(string $key): bool
    {
        return $this->present[$key] ?? false;
    }

    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    /** Для fill() в модели */
    public function toModelArray(): array
    {
        $out = [];
        foreach ($this->present as $k => $_) {
            if ($k === 'created_at') {
                continue;
            }
            $out[$k] = $this->data[$k];
        }
        return $out;
    }

    public function wantsCustomCreatedAt(): bool
    {
        return $this->has('created_at');
    }

    public function createdAt(): ?Carbon
    {
        return $this->get('created_at');
    }

    public function toArray(): array
    {
        return [
            'application_number' => $this->applicationNumber,
            'consultation_date' => $this->consultationDate,
            'from_institution_id' => $this->fromInstitutionId,
            'institution_id' => $this->institutionId,
            'specialist_id' => $this->specialistId,
            'patient_name' => $this->patientName,
            'patient_birth_year' => $this->patientBirthYear,
            'diagnosis_id' => $this->diagnosisId,
            'created_at' => $this->createdAt,
        ];
    }
}
