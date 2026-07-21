<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

class Devoirs extends Model
{
    protected $guarded = [];

    public function devoirs_type() {
        return $this->belongsTo(DevoirsType::class);
    }

    public function get_classe () {
        return $this->belongsTo(GetClasse::class);
    }

    public function level_matter () {
        return $this->belongsTo(LevelMatter::class);
    }
}
