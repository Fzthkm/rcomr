<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $status = $this->status instanceof \BackedEnum ? $this->status->value : $this->status;

        return [
            'id' => (int) $this->id,
            'number' => (string) $this->application_number,

            // дата: и ISO, и human
            'consultation_date' => [
                'iso' => $this->consultation_date?->toDateString(),
                'human' => $this->consultation_date?->format('d.m.Y'),
            ],

            'patient' => [
                'name' => $this->patient_name,
                'birth_year' => $this->patient_birth_year ? (int) $this->patient_birth_year : null,
            ],

            'status' => [
                'code' => $status,
                'label' => self::statusLabel($status),
            ],

            // диагноз только если подгружен
            'diagnosis' => $this->whenLoaded('diagnosis', function () {
                return [
                    'id' => $this->diagnosis_id ? (int) $this->diagnosis_id : null,
                    'name' => $this->diagnosis->name,
                ];
            }, null),

            // учреждения
            'institution' => [
                'from' => $this->whenLoaded('referredFromInstitution', function () {
                    return [
                        'id' => $this->referred_from_institution_id ?? null,
                        'name' => $this->referredFromInstitution->name,
                    ];
                }, null),
                'to' => $this->whenLoaded('requestedByInstitution', function () {
                    return [
                        'id' => $this->institution_id ?? null,
                        'name' => $this->requestedByInstitution->name,
                    ];
                }, null),
            ],

            'specialist' => $this->whenLoaded('specialist', function () {
                return [
                    'id' => $this->specialist_id ?? null,
                    'name' => $this->specialist->name,
                ];
            }, null),

            // удобно для кнопки "дублировать"
            'links' => [
                'duplicate' => route('applications.create', ['from' => $this->id]),
            ],
        ];
    }

    private static function statusLabel(string|int|null $code): string
    {
        // подправь под свои значения
        return match ($code) {
            'created', 1 => 'Создана',
            'canceled', 0 => 'Отменена',
            default => 'Неизвестно',
        };
    }
}
