<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialist extends Model
{
    protected $table = 'specialists';
    protected $fillable = [
        'name',
        'phone',
        'education'
    ];

    public function workplace()
    {
        return $this->belongsTo(MedicalInstitution::class, 'workplace_id', 'id');
    }
}
