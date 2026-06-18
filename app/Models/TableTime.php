<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableTime extends Model
{
    protected $guarded = [];

    public function level_matter() {
        return $this->belongsTo(LevelMatter::class);
    }
}
