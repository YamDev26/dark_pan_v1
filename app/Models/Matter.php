<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matter extends Model
{
    protected $guarded = [];

    public function bilanMatter() {
        return $this->belongsTo(BilanMatter::class);
    }
}
