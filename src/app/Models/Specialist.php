<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specialist extends Model
{
    use SoftDeletes;

    protected $table = 'specialists';
    protected $fillable = [
        'name',
        'specialization_id',
        'workplace_id',
        'education',
        'additional_info',
    ];

    public function workplace(): BelongsTo
    {
        return $this->belongsTo(MedicalInstitution::class, 'workplace_id', 'id');
    }

    public function specialization(): BelongsTo
    {
        return $this->belongsTo(Specialization::class, 'specialization_id', 'id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'specialist_id', 'id');
    }
}
