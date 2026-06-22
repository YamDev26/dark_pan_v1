<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GetClasse extends Model
{
    protected $guarded = [];

    public function level() {
        return $this->belongsTo(Level::class);
    }

    public function serie() {
        return $this->belongsTo(Serie::class);
    }


    public function school_year() {
        return $this->belongsTo(SchoolYear::class);
    }
}
