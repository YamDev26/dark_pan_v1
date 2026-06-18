<?php
  namespace App\Services;

  use App\Models\School;
  use App\Models\SchoolYear;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;
  use Yajra\DataTables\Facades\DataTables;

  class ScheduleService
  {
    private $schl; private const TEACHER = 8;
    public function __construct() {
      $this->schl = Auth::user()->school_id ?? 1;
    }
    
    
    public function getDataTable() {
      $query = $this->getTeacher();
      $compte = 0;
      return DataTables::of($query)
      ->addColumn('compte', function() use (&$compte) {
        return ($compte < 9 ? '0'.++$compte : ++$compte);
      })
      ->addColumn('name', function ($row) {
        return (
          ucwords($row->civility).' '.
          strtoupper($row->first_name)
          .' '.ucwords($row->last_name)
        );
      })
      ->addColumn('horaire', function ($row) {
        return (
          ($row->heures < 10 ? '0'.$row->heures:$row->heures).'H'
        );
      })
      ->addColumn('action', function ($row) {
        $url = route('horraire.show', $row->id);
        return ('<a href="'.$url.'" class="btn btn-sm btn-light text-white py-0">Detail</a>');
      })
      ->rawColumns(['compte', 'name', 'horaire', 'action'])
      ->make(true);
    }


    public function getHoraireClasse($teacher) {
      return DB::table('classe_teachers as ct')
      ->join('get_classes as gc', 'gc.id', '=', 'ct.get_classe_id')
      ->where('gc.school_year_id', $this->yearActif())
      ->where('ct.user_id', $teacher)
      ->select(
        'gc.libelle',
        DB::raw('COUNT(*) as heures')
      )
      ->groupBy('gc.id', 'gc.libelle')
      ->orderBy('gc.level_id')
      ->get();
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

    private function school() {
      return School::find($this->schl) ?? null;
    }
  }