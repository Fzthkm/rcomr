<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicalInstitution extends Model
{
    protected $table = 'medical_institutions';
    protected $fillable = ['name'];

    public function requestedApplications(): HasMany
    {
        return $this->hasMany(Application::class, 'institution_id');
    }

    public function referredApplications(): HasMany
    {
        return $this->hasMany(Application::class, 'from_institution_id');
    }
}
