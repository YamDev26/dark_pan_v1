<?php
  namespace App\Services;

  use App\Models\Serie;
  use App\Models\Level;
  use App\Models\School;
  use App\Models\Register;
  use App\Models\GetClasse;
  use App\Models\SchoolYear;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;
  use Yajra\DataTables\Facades\DataTables;
  class RegisterService
  {
    private const ACTIF  = 1;
    private $schl;
    public function __construct() {
      $this->schl = Auth::user()->school_id ?? 1;
    }

    public function school() {
      return School::find($this->schl);
    }


    public function getYajra() {
      $query = DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->join('get_classes as gc', 'gc.id', '=', 'r.get_classe_id')
      ->select(['r.id', 's.matricul','s.first', 's.last', 's.genre', 'gc.libelle'])
      ->where([
        'gc.school_year_id' => $this->year(),
        'gc.school_id' => $this->schl
      ])->orderBy('r.created_at','DESC')->get();
      return $this->yarjaTable($query);
    }

    public function getSearch($level) {
      $query = DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->join('get_classes as gc', 'gc.id', '=', 'r.get_classe_id')
      ->select(['r.id', 's.matricul','s.first', 's.last', 's.genre', 'gc.libelle'])
      ->where([
        'gc.school_year_id' => $this->year(),
        'gc.school_id' => $this->schl,
        'gc.level_id' => $level
      ])->orderBy('r.created_at','DESC')->get();
      return $this->yarjaTable($query);
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
      return Level::find($str);
    }

    public function search($matricul) {
      $query = DB::table('school_students as ss')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->select(['ss.id', 's.matricul', 's.first', 's.last', 's.genre', 's.date', 's.lieu'])
      ->where([
        'ss.school_id' => $this->schl,
        's.matricul' => $matricul,
        'ss.status' => '1',
      ])->first();
      if(!$query) {
        return false;
      }
      return [
        'student' => $query,
        'class' => $this->verifyRegister($query->id),
      ];
    }

    public function getStore($student, $class, $affect, $redoublant, $boursier, $interne, $lv2 = null) {
      $classe = $this->classe($class);
      if($classe['effectif'] > $classe['inscrit']) {
        Register::firstOrCreate([
          'school_student_id' => $student,
          'get_classe_id' => $class
        ], [
          'affecte' => $affect == 'oui' ? true:false,
          'redoubant' => $redoublant == 'oui' ? true:false,
          'boursier' => $boursier == 'oui' ? true:false,
          'interne' =>$interne == 'oui' ? true:false,
          'lv2' => $lv2
        ]);
        // Updatde GetClasse And Attribut Inscrit
        $this->updateClasse($classe);
      }
      else {
        $classe->update(['invalid' => '0']);
      }
    }

    public function getClasse($level, $lv2 = null, $serie = null,) {
      return GetClasse::query()
      ->where([
        'school_year_id' => $this->year(),
        'school_id'      => $this->schl,
        'level_id'       => $level,
        'invalid'        => (string)self::ACTIF,
        'status'         => (string)self::ACTIF,
      ])
      ->when($serie, fn ($query) => $query->where('serie_id', $serie))
      ->when($lv2, fn ($query) => $query->whereIn('lv2', [$lv2, 'mix']))
      ->orderBy('id')
      ->get();
    }

    public function getSerie($symbol) {
      return Serie::query()
      ->where($symbol, (string)self::ACTIF)
      ->orderBy('id')
      ->get();
    }

    public function getRegister($str) {
      return Register::find($str) ?? null;
    }

    public function destroy($str) {
      $dts = Register::find($str);
      if($dts) {
        $dts->delete();
        return true;
      }
      return false;
    }

    private function updateClasse($dts) {
      if($dts) {
        $dts->update([ 'inscrit' => ($dts['inscrit'] + 1) ]);
      }
    }

    private function verifyRegister($student) {
      return Register::query()
      ->join('get_classes as gc', 'gc.id', '=', 'registers.get_classe_id')
      ->where('registers.school_student_id', $student)
      ->where('gc.school_year_id', $this->year())
      ->value('gc.libelle');
    }

    private function classe($str) {
      return GetClasse::find($str) ?? null;
    }


    private function year() {
      return SchoolYear::where('status', '1')->value('id') ?? null;
    }

    private function yarjaTable($query) {
      $compte = 0;
      return DataTables::of($query)
      ->addColumn('compte', function() use (&$compte) {
        return ($compte < 9 ? '0'.++$compte : ++$compte);
      })
      ->addColumn('matricul', function ($row) {
        return ($row->matricul);
      })
      ->addColumn('first', function ($row) {
        return (strtoupper($row->first));
      })
      ->addColumn('last', function ($row) {
        return (ucwords($row->last));
      })
      ->addColumn('genre', function ($row) {
        return (ucwords($row->genre == 'F' ? 'Feminin':'Masculin'));
      })
      ->addColumn('classe', function ($row) {
        return ($row->libelle);
      })
      ->addColumn('action', function ($row) {
        return ('<span class="card-block remove-label m-0 pb-0 text-center">
          <button data-id="'.$row->id.'" class="btn btn-sm btn-outline-light dtlBtn py-0 me-2">Detail</button>
        </span>');
      })
      ->rawColumns(['compte', 'matricul', 'first', 'last', 'genre', 'classe', 'action'])
      ->make(true);
      // <button data-id="'.$row->id.'" class="btn btn-sm btn-primary me-2 deleteBtn">Delete</button>
    }
  }