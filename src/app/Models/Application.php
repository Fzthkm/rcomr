<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель заявки
 *
 * @property int $application_number
 * @property Carbon $consultation_date
 * @property int $institution_id
 * @property int|null $from_institution_id
 * @property int $specialist_id
 * @property string $patient_name
 * @property string|null $patient_birth_year
 * @property int $diagnosis_id
 * @property ApplicationStatus $status
 */
class Application extends Model
{
    protected $table = 'applications';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'consultation_date' => 'date',
        'status' => ApplicationStatus::class,
    ];

    public function requestedByInstitution(): BelongsTo
    {
        return $this->belongsTo(MedicalInstitution::class, 'institution_id');
    }

    public function referredFromInstitution(): BelongsTo
    {
        return $this->belongsTo(MedicalInstitution::class, 'from_institution_id');
    }

    public function specialist(): BelongsTo
    {
        return $this->belongsTo(Specialist::class);
    }

    public function diagnosis(): BelongsTo
    {
        return $this->belongsTo(Diagnosis::class);
    }

}
