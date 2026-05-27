<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $guarded = [];

    public function get_classe() {
        $dts = $this->hasMany(GetClasse::class, 'level_id');
        return $dts->count() < 10 ? '0'.$dts->count():$dts->count(); 
    }
}
