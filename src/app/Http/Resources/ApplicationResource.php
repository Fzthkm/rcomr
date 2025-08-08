<?php

namespace App\Http\Resources;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'application_number' => $this->application_number,
            'consultation_date' => $this->consultation_date?->format('d.m.Y'),
            'patient_name' => $this->patient_name,
            'status' => $this->status,
            'diagnosis' => $this->diagnosis->name ?? '—',
            'institution' => $this->requestedByInstitution->name ?? '—',
            'from_institution' => $this->referredFromInstitution->name ?? '—',
            'specialist' => $this->specialist->name ?? '—',
        ];
    }
}
