<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuttingSchoolYear extends Model
{
    protected $guarded = [];

    public function cutting() {
        return $this->belongsTo(Cutting::class);
    }

    public function school_year() {
        return $this->belongsTo(SchoolYear::class);
    }
}
