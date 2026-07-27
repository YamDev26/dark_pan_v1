<?php
  namespace App\Services;

  use App\Models\User;
  use App\Models\School;
  use App\Models\SlotTime;
  use App\Models\DaysWeek;
  use App\Models\SchoolYear;
  use Illuminate\Support\Facades\DB;
  use Yajra\DataTables\Facades\DataTables;

  class ScheduleService
  {
    private $schl; private const TEACHER = 8;
    public function __construct() {
      $user = getUserGlobal();
      $this->schl = $user ? $user->school_id:null;
    }

    public function school() {
      return School::find($this->schl);
    }

    public function schoolYear() {
      $data = SchoolYear::where('status', '1')->first();
      return $data->libelle;
    }

    public function getUser($str) {
      return User::find($str);
    }

    public function getDayWeek() {
      return DaysWeek::orderBy('order')->get();
    }

    public function getTime() {
      $times = SlotTime::where('school_id', $this->schl)
      ->orderBy('order')
      ->get()
      ->groupBy('period');
      return [$times->get(1, collect()), $times->get(2, collect())];
    }


    public function getTableTime($user) {
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
        ['ct.user_id', '=', $user],
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

    
    public function getDataTable() {
      $query = $this->getTeacher();
      $compte = 0;
      return DataTables::of($query)
      ->addColumn('compte', function() use (&$compte) {
        return ($compte < 9 ? '0'.++$compte : ++$compte);
      })
      ->addColumn('first', function ($row) {
        return (strtoupper($row->first_name));
      })
      ->addColumn('last', function ($row) {
        return (ucwords($row->last_name));
      })
      ->addColumn('sexe', function ($row) {
        return ($row->civility == 'mr' ? 'Homme':'Femme');
      })
      ->addColumn('classe', function ($row) {
        return (sprintf('%02d', $row->heures));
      })
      ->addColumn('horaire', function ($row) {
        return (sprintf('%02d', $row->heures).'H');
      })
      ->addColumn('action', function ($row) {
        $url = route('horraire.show', $row->id);
        return ('<a href="'.$url.'" class="btn btn-sm btn-outline-light py-0">
          <i class="fas fa-ellipsis-h"></i>
        </a>');
      })
      ->rawColumns(['compte', 'first', 'last', 'sexe', 'classe', 'horaire', 'action'])
      ->make(true);
    }
    


    private function getTeacher() {
      return DB::table('users as u')
      ->leftJoin('classe_teachers as ct', 'ct.user_id', '=', 'u.id')
      ->leftJoin('get_classes as gc', function ($join) {
        $join->on('gc.id', '=', 'ct.get_classe_id')
        ->where('gc.school_year_id', $this->yearActif());
      })
      ->where([
        'u.school_id' => $this->schl,
        'u.role_id' => self::TEACHER,
        'u.status' => '1',
      ])
      ->select(
        'u.id',
        'u.first_name',
        'u.last_name',
        'u.civility',
        DB::raw('COUNT(gc.id) as heures')
      )
      ->groupBy(
        'u.id',
        'u.first_name',
        'u.last_name',
        'u.civility'
      )
      ->orderBy('u.first_name')
      ->orderBy('u.last_name')
      ->get();
    }

    private function yearActif() {
      $year = SchoolYear::where('status', '1')->first();
      return $year ? $year->id:null;
    }
  }