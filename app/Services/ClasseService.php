<?php
  namespace App\Services;

  use App\Models\user;
  use App\Models\Serie;
  use App\Models\Level;
  use App\Models\School;
  use App\Models\DaysWeek;
  use App\Models\SlotTime;
  use App\Models\TableTime;
  use App\Models\GetClasse;
  use App\Models\SchoolYear;
  use App\Models\ClasseTeacher;
  use Illuminate\Support\Facades\DB;
  use Yajra\DataTables\Facades\DataTables;
  class ClasseService
  {
    private const U_ROLE  = 8;
    private $schl;

    public function __construct() {
      $user = getUserGlobal();
      $this->schl = $user ? $user->school_id:null;
    }

    public function school() {
      return School::find($this->schl) ?? null;
    }


    public function getStudentClasse($str) {
      return DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->select([
        'ss.id', 's.matricul','s.first', 's.last', 's.genre', 's.date', 
        's.lieu', 'r.affecte', 'r.redoubant', 'r.boursier', 'r.lv2'
      ])
      ->where('r.get_classe_id', $str)->orderBy('s.first')->orderBy('s.last')->get();
    }


    public function dataTable($str) {
      $query = $this->getStudentClasse($str);
      $compte = 0;
      return DataTables::of($query)
      ->addColumn('compte', function() use (&$compte) {
        return ($compte < 9 ? '0'.++$compte : ++$compte);
      })
      ->addColumn('matricul', function ($row) {
        return ($row->matricul);
      })
      ->addColumn('name', function ($row) {
        return (strtoupper($row->first).' '.ucwords($row->last));
      })
      ->addColumn('genre', function ($row) {
        return (ucwords($row->genre == 'F' ? 'Feminin':'Masculin'));
      })
      ->addColumn('naissance', function ($row) {
        return (date('d/m/Y', strtotime($row->date)));
      })
      ->addColumn('affect', function ($row) {
        return (ucwords($row->affecte ? 'oui':'non'));
      })
      ->addColumn('redoublant', function ($row) {
        return (ucwords($row->redoubant ? 'oui':'non'));
      })
      ->rawColumns(['compte', 'matricul', 'name', 'genre', 'naissance', 'affect', 'redoublant'])
      ->make(true);
    }


    public function getTeacherClasse($classe) {
      return DB::table('classe_teachers as ct')
      ->join('users as u', 'u.id', '=', 'ct.user_id')
      ->join('level_matters as lm', 'lm.id', '=', 'ct.level_matter_id')
      ->join('matters as m', 'm.id', '=', 'lm.matter_id')
      ->select([
        'u.first_name', 'u.last_name','u.civility', 'm.symbol', 'ct.checked'
      ])
      ->where('ct.get_classe_id', $classe)
      ->orderBy('m.bilan_matter_id')
      ->orderBy('m.position')
      ->get();
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


    public function getClass($id) {
      $dts = GetClasse::where('level_id', $id)->where('school_id', $this->schl)->where('school_year_id', $this->year())->get();
      return $dts ?? [];
    }


    public function classe($str) {
      $dts = GetClasse::find($str);
      return $dts ?? null;
    }


    public function getSerie($str) {
      $dts = Serie::where($str, '1')->orderBy('id')->get();
      return $dts ?? [];
    }


    public function getStore($id, $number, $nbre, $lv2 = null, $serie = null) {
      $i = 0;
      while($i < $nbre) {
        GetClasse::create([
          'lv2' => $lv2,
          'level_id' => $id,
          'serie_id' => $serie,
          'effectif' => $number,
          'libelle' => $this->libClass($id, $serie),
          'school_year_id' => $this->year(),
          'school_id' => $this->schl,
        ]);
        $i++;
      }
    }


    public function update($data) {
      $dt = GetClasse::find($data['id']);
      if($dt['inscrit'] <= $data['number']) {
        $dt->update([
          'lv2' => $data['lv2'],
          'effectif' => $data['number'],
          'status' => $data['status'] ? '1':'0',
          'invalid' => ($dt['effecif'] > $dt['inscrit']) ? '1':'0'
        ]);
      }
    }


    public function delete($str) {
      $dts = GetClasse::find($str);
      if($dts['status']){
        return false;
      }
      $dts->delete();
      return true;
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


    public function getMatters($level, $serie = null) {
      return DB::table('level_matters as lm')
      ->join('matters as m', 'm.id', '=', 'lm.matter_id')
      ->select(
        'lm.id',
        'm.libelle',
        'm.symbol',
        'lm.value'
      )
      ->where('lm.school_id', $this->schl)
      ->where('lm.level_id', $level)
      ->when(
        $serie !== null,
        fn ($query) => $query->where('lm.serie_id', $serie),
        fn ($query) => $query->whereNull('lm.serie_id')
      )
      ->where('m.libelle', '!=', 'conduite')
      ->orderBy('m.bilan_matter_id')
      ->orderBy('m.position')
      ->get();
    }


    public function getTableTime($classe) {
      return TableTime::where('get_classe_id', $classe)
      ->orderBy('days_week_id')
      ->orderBy('slot_time_id')
      ->orderBy('period')
      ->get();
    }


    public function getTeachers($str, $status = '1') {
      return DB::table('users as u')
      ->leftJoin('classe_teachers as ct', function ($join) use ($str) {
        $join->on('u.id', '=', 'ct.user_id')
        ->where('ct.get_classe_id', $str);
      })
      ->where([
        'u.status' => $status, 
        'u.role_id' => self::U_ROLE,
        'u.school_id' => $this->schl
      ])
      ->select([
        'u.id', 'u.first_name', 'u.last_name', 'u.civility',
        'ct.level_matter_id as matter', 'ct.checked'
      ])
      ->orderBy('u.first_name')->orderBy('u.last_name')->get();
    }


    public function teachesStore($matters, $users, $checked, $classe) {

      $this->deleteUser($classe); // Vider la table 

      foreach($users as $i => $item) {

        if(is_null($item)) {
          continue;
        }

        list($user, $str) = explode('_', $item, 2);
        ClasseTeacher::updateOrCreate([
          'user_id' => $user,
          'get_classe_id' => $classe,
          'level_matter_id' => $matters[$i]
        ],
        [
          'checked' => ($str == $checked) ? true:false
        ]);
      }
    }


    public function storeTime($data, $classe) {

      TableTime::where('get_classe_id', $classe)->delete();
      foreach($data as $item) {
        list($matter, $time, $day, $other) = explode('_', $item, 4);
        TableTime::updateOrCreate([
          'days_week_id' => $day,
          'slot_time_id' => $time,
          'get_classe_id' => $classe,
          'level_matter_id' => $matter
        ],
        [
          'period' => $other
        ]);
      }
    }


    private function libClass($id, $serie = null) {
      $level = $this->level($id);
      $dt = $serie ? Serie::find($serie):null;
      $lib = $serie ? ((in_array($dt->libelle, ['A1', 'A2'])) ? 'A':$dt->libelle):null;
      $nbre = $this->count($id, ( $serie ? (in_array($lib, ['A1', 'A2']) ? 1:$serie):null));
      return $level['symbol'].$lib.$nbre;
    }


    private function count($id, $serie = null) {
      $query = GetClasse::where('level_id', $id)
      ->where('school_id', $this->schl)
      ->where('school_year_id', $this->year());

      in_array($serie, [1, 2, 3])
      ? $query->whereIn('serie_id', [1, 2, 3])
      : $query->where('serie_id', $serie);
      return $query->count() + 1;
    }


    private function year() {
      $year = SchoolYear::where('status', '1')->first();
      return $year ? $year->id:null;
    }

    private function deleteUser($classe) {
      ClasseTeacher::where('get_classe_id', $classe)
      ->delete();
    }
  }