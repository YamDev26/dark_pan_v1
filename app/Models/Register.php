<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    protected $guarded = [];

    protected $casts = [
        'affecte' => 'boolean',
        'redoubant' => 'boolean',
        'boursier' => 'boolean',
        'interne' => 'boolean',
    ];

    public function get_classe() {
        return $this->belongsTo(GetClasse::class);
    }

    public function school_student() {
        return $this->belongsTo(SchoolStudent::class);
    }
}
