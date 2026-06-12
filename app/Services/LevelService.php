<?php
  namespace App\Services;

  use App\Models\Level;
  use App\Models\Serie;
  use App\Models\Matter;
  use App\Models\School;
  use App\Models\LevelMatter;
  use Illuminate\Support\Facades\DB;
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
      ->where(function ($query) use ($school) {
        $query->where('cycle1', $school['cycle1'])
        ->orWhere('cycle2', $school['cycle2']);
      })
      ->orderBy('id')
      ->get();
      return $levels ?? [];
    }


    public function level($str) {
      return Level::find($str) ?? null;
    }

    public function serie($id) {
      return Serie::find($id);
    }

    public function getMatters() {
      $school = $this->school();
      $excluded = [];
      if (!$school['informatik']) {
        $excluded[] = 12; // id Informatique
      }
      if (!$school['autres']) {
        $excluded[] = 10; // id Musique/Arts Plastique
      }

      return Matter::query()
      ->where('id', '<', 13)
      ->when($excluded, fn ($q) => $q->whereNotIn('id', $excluded))
      ->orderByRaw('bilan_matter_id, position')
      ->get();
    }

    public function getDataCycle1($levelId) {
      $dts = LevelMatter::where('school_id', $this->schl)->where('level_id', $levelId)->orderBy('id')->get();
      return $dts ?? [];
    }

    public function getDataCycle2($id, $libelle) {
      return DB::table('series as s')
      ->leftJoin('level_matters as lm', function ($join) use ($id) {
        $join->on('lm.serie_id', '=', 's.id')
        ->where(['lm.level_id' => $id, 'lm.school_id' => $this->schl]);
      })
      ->leftJoin('matters as m', 'm.id', '=', 'lm.matter_id')
      ->leftJoin('bilan_matters as bm', 'bm.id', '=', 'm.bilan_matter_id')
      ->select(
        's.id','s.libelle as serie', 'm.libelle as matter', 'm.symbol as symbol',
        'bm.libelle as bilan', 'lm.value'
      )
      ->where(['s.'.$libelle => '1'])
      ->orderByRaw('s.id, m.bilan_matter_id, m.position')
      ->get()
      ->groupBy('id')
      ->map(function ($items) {
        return [
          'id' => $items->first()->id,
          'serie' => $items->first()->serie,
          'matters' => $items
          ->whereNotNull('matter')
          ->map(fn ($item) => [
            'matter' => $item->matter,
            'bilan'  => $item->bilan,
            'symbol' => $item->symbol,
            'value'  => $item->value,
          ])->values(),
        ];
      })
      ->values();
    }

    public function getStore($str, $matters, $coeffs) {
      $str = explode('_', $str);
      foreach ($matters as $index => $matterId) {
        LevelMatter::updateOrCreate([
            'level_id' => $str[0],
            'matter_id' => $matterId,
            'school_id' => $this->schl,
            'serie_id' => sizeof($str) > 1 ? $str[1]:null,
          ],
          ['value' => $coeffs[$index] ]
        );
      }
    }
    

    private function school() {
      return School::find($this->schl) ?? null;
    }
  }