<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelMatter extends Model
{
    protected $guarded = [];

    public function matter() {
        return $this->belongsTo(Matter::class);
    }
}
