<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluated extends Model
{
    protected $guarded = [];

    public function get_classe() {
        return $this->belongsTo(GetClasse::class);
    }

    public function level_matter() {
        return $this->belongsTo(LevelMatter::class);
    }

    public function evaluated_type() {
        return $this->belongsTo(EvaluatedType::class);
    }


    public function cutting_school_year() {
        return $this->belongsTo(CuttingSchoolYear::class);
    }
}
