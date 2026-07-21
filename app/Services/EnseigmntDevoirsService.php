<?php
  namespace App\Services;

  use App\Models\User;
  use App\Models\School;
  use App\Models\Devoirs;
  use App\Models\SlotTime;
  use App\Models\DaysWeek;
  use App\Models\SchoolYear;
  use App\Models\DevoirsType;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;

  class EnseigmntDevoirsService
  {
    private $user;
    public function __construct() {
      $this->user = Auth::user();
    }

    public function school() {
      return School::find($this->user->school_id);
    }

    public function typeDevoirs() {
      return DevoirsType::get();
    }

    public function schoolYear() {
      $data = SchoolYear::where('status', '1')->first();
      return $data->libelle;
    }

    public function getUser() {
      return User::find($this->user->id);
    }

    public function getDayWeek() {
      return DaysWeek::orderBy('order')->get();
    }

    public function getTime() {
      $times = SlotTime::where('school_id', $this->user->school_id)
      ->orderBy('order')
      ->get()
      ->groupBy('period');
      return [$times->get(1, collect()), $times->get(2, collect())];
    }


    public function getTableTime() {
      $schoolYearId = $this->yearActif();
      return DB::table('classe_teachers as ct')
      ->join('get_classes as gc', 'gc.id', '=', 'ct.get_classe_id')
      ->join('level_matters as lm', 'lm.id', '=', 'ct.level_matter_id')
      ->join('matters as m', 'm.id', '=', 'lm.matter_id')
      ->join('table_times as tt', function ($join) {
        $join->on('tt.level_matter_id', '=', 'ct.level_matter_id')
        ->on('tt.get_classe_id', '=', 'ct.get_classe_id');
      })
      ->where([
        ['ct.user_id', '=', $this->user->id],
        ['gc.school_year_id', '=', $schoolYearId],
      ])
      ->select([
        'gc.libelle as classe',
        'tt.days_week_id as days',
        'tt.slot_time_id as time',
        'm.symbol as matter',
        'tt.period',
      ])
      ->get();
    }


    public function getClasses() {
      return DB::table('classe_teachers as ct')
      ->join('get_classes as gc', 'gc.id', '=', 'ct.get_classe_id')
      ->where([
        'gc.school_year_id' => $this->yearActif(),
        'gc.school_id' => $this->user->school_id,
        'ct.user_id' => $this->user->id,
      ])
      ->select('gc.libelle', 'gc.id')
      ->distinct('gc.id')
      ->get();
    }


    public function getMatters($class) {
      return DB::table('classe_teachers as ct')
      ->join('get_classes as gc', 'gc.id', '=', 'ct.get_classe_id')
      ->join('level_matters as lm', 'lm.id', '=', 'ct.level_matter_id')
      ->join('matters as m', 'm.id', '=', 'lm.matter_id')
      ->where([
        'ct.get_classe_id' => $class,
        'ct.user_id' => $this->user->id,
      ])
      ->select('m.symbol', 'lm.id')
      ->get();
    }


    public function verifyTimeDevoirs($classe, $cutting, $date, $debut) {
      return Devoirs::where('get_classe_id', $classe)->where('cutting_school_year_id', $cutting)
      ->where('dates', $date)->where('debut', $debut)->count();
    }


    public function getDevoirs($str) {
      return Devoirs::find($str);
    }


    public function getDevoirsUser() {
      $datas = DB::table('cutting_school_years as cs')
      ->leftJoin('cuttings as c', 'c.id', '=', 'cs.cutting_id')
      ->leftJoin('devoirs as d', function ($join) {
        $join->on('d.cutting_school_year_id', '=', 'cs.id')
        ->join('classe_teachers as ct', 'ct.get_classe_id', '=', 'd.get_classe_id')
        ->where(['ct.user_id' => $this->user->id]);
      })
      ->leftJoin('devoirs_types as dt', 'dt.id', '=', 'd.devoirs_type_id')
      ->leftJoin('level_matters as lm', 'lm.id', '=', 'd.level_matter_id')
      ->leftJoin('get_classes as gc', 'gc.id', '=', 'd.get_classe_id')
      ->leftJoin('matters as m', 'm.id', '=', 'lm.matter_id')
      ->select(
        'cs.id', 'cs.status as actif', 'c.libelle as cutting', 'd.id as devoir', 'd.dates',
        'd.times', 'd.debut','d.status as status',  'dt.libelle as libelle', 'm.symbol', 'gc.libelle as classe'
      )
      ->orderByRaw('cs.id, d.dates')
      ->distinct()
      ->get()
      ->groupBy('id')
      ->map(function ($items) {
        return [
          'id' => $items->first()->id,
          'cutting' => $items->first()->cutting,
          'actif' => $items->first()->actif,
          'devoirs' => $items
          ->whereNotNull('libelle')
          ->map(fn ($item) => [
            'libelle' => $item->libelle,
            'symbol' => $item->symbol,
            'classe'  => $item->classe,
            'status'  => $item->status,
            'times'  => $item->times,
            'debut'  => $item->debut,
            'date'  => $item->dates,
            'id'  => $item->devoir
          ])->values(),
        ];
      })
      ->values();
      return $datas ?? [];
    }


    public function devoirSotre($data) {
      Devoirs::updateOrCreate([
        'dates' => $data['date'],
        'debut' => $data['debut'],
        'get_classe_id' => $data['classe'],
        'cutting_school_year_id' => $data['cutting'],
      ],
      [
        'times' => $data['times'],
        'level_matter_id' => $data['matter'],
        'devoirs_type_id' => $data['type'],
      ]);
    }

    public function destroy($data) {
      return $data->delete();
    }

    private function yearActif() {
      $year = SchoolYear::where('status', '1')->first();
      return $year ? $year->id:null;
    }
  }