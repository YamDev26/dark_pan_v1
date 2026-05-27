<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolStudent extends Model
{
    protected $guarded = [];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function tuteur() {
        return $this->belongsTo(Tuteur::class);
    }
}
