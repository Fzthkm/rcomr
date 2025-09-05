<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialization extends Model
{
    protected $table = 'specializations';

    public function specialists(): HasMany
    {
        return $this->hasMany(Specialist::class, 'specialization_id');
    }
}
