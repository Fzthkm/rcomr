<?php

namespace App\DTOs;

use App\DTOs\Interfaces\BaseDtoInterface;
use Illuminate\Foundation\Http\FormRequest;

class SpecialistDTO implements BaseDtoInterface
{
    public function __construct(
        public string $name,
        public int $workplaceId,
        public int $specializationId,
        public ?string $education,
        public ?string $additionalInfo,
    ) {
    }

    public static function fromRequest(FormRequest $request): static
    {
        return new self(
            name: $request->input('name'),
            workplaceId: $request->input('workplace_id'),
            specializationId: $request->input('specialization_id'),
            education: $request->input('education'),
            additionalInfo: $request->input('additional_info'),
        );
    }


    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'workplace_id' => $this->workplaceId,
            'specialization_id' => $this->specializationId,
            'education' => $this->education,
            'additional_info' => $this->additionalInfo,
        ];
    }
}
