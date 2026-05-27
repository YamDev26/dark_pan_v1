<?php
  namespace App\Services;

  use App\Models\Level;
  use App\Models\Matter;
  use App\Models\School;
  use App\Models\LevelMatter;
  use Illuminate\Support\Facades\Auth;
  class LevelService
  {
    private $schl;
    public function __construct() {
      $this->schl = Auth::user()->school_id ?? 1;
    }
    
    public function getLevels() {
      $school = $this->school();
      $levels = Level::query()
      ->when($school['cycle1'], function ($q) use ($school) {
        $q->where('cycle1', $school['cycle1']);
      })
      ->when($school['cycle2'], function ($q) use ($school) {
        $q->orWhere('cycle2', $school['cycle2']);
      })
      ->orderBy('id')->get();
      return $levels ?? [];
    }


    public function level($str) {
      return Level::find($str) ?? null;
    }


    public function getMatters() {
      $data = Matter::where('officiel', '1')->where('libelle', '!=', 'conduite')->orderBy('libelle')->get();
      return $data ?? [];
    }

    public function getData($levelId) {
      $dts = LevelMatter::where('school_id', $this->schl)->where('level_id', $levelId)->orderBy('id')->get();
      return $dts ?? [];
    }

    public function getStore($str, $matters, $coeffs, $serie = null) {
      foreach ($matters as $index => $matterId) {
        LevelMatter::updateOrCreate([
            'level_id' => $str,
            'matter_id' => $matterId,
            'school_id' => $this->schl,
            'serie_id' => $serie,
          ],
          ['value' => $coeffs[$index] ]
        );
      }
    }
    

    private function school() {
      return School::find($this->schl) ?? null;
    }
  }