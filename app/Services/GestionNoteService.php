<?php
  namespace App\Services;

  use App\Models\Evaluat;
  use App\Models\GetClasse;
  use App\Models\Evaluated;
  use App\Models\LevelMatter;
  use App\Models\CuttingSchoolYear;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;
  use Yajra\DataTables\Facades\DataTables;
  class GestionNoteService
  {
    private $schl;
    public function __construct() {
      $this->schl = Auth::user()->school_id ?? 1;
    }

    
    public function getNote($evaluat) {
      
      $query = $this->studentGet($evaluat);
      $compte = 0;
      return DataTables::of($query)
      ->addColumn('compte', function() use (&$compte) {
        return ($compte < 9 ? '0'.++$compte : ++$compte);
      })
      ->addColumn('matricule', function ($row) {
        return ($row->matricul);
      })
      ->addColumn('first', function ($row) {
        return (strtoupper($row->first));
      })
      ->addColumn('last', function ($row) {
        return (ucwords($row->last));
      })
      ->addColumn('genre', function ($row) {
        return ($row->genre == 'F' ? 'Feminin':'Masculin');
      })
      ->addColumn('note', function ($row) {
        return (
          $row->note == 'nc' ? $row->note:
          ($row->note ? $row->note:'--').' / '.(($row->value * 20) != 0 ? ($row->value * 20):'--')
        );
      })
      ->rawColumns(['compte', 'matricule', 'first', 'last', 'genre', 'note'])
      ->make(true);
    }

    public function existNote($evaluat) {
      $exists = Evaluat::where('evaluated_id', $evaluat)->exists();
      return $exists;
    }

    public function getStudent($classe) {
      $query = DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->where('r.get_classe_id', $classe)
      ->select(['r.id', 's.matricul', 's.first', 's.last', 's.genre'])
      ->orderByRaw('s.first, s.last')
      ->get();
      return $query;
    }


    public function getNotStudent($evaluat) {
      return $this->studentGet($evaluat);
    }


    public function studentId($matricul, $classe) {
      $dt = DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->select(['r.id', 's.matricul','s.genre'])
      ->where('r.get_classe_id', $classe)->where('s.matricul', $matricul)
      ->first();
      return $dt ?? null;
    }
    

    public function noteEvaluat($register, $evaluat, $note = null): void {
      Evaluat::updateOrCreate(
        [
          'register_id' => $register,
          'evaluated_id' => $evaluat,
        ],
        [
          'note' => $note,
        ]
      );
    }

    public function evaluated($str) {
      return Evaluated::find($str);
    }

    public function classe($classe) {
      return GetClasse::find($classe);
    }

    public function matter($matter) {
      return LevelMatter::find($matter);
    }

    public function cutting($cutting) {
      return CuttingSchoolYear::find($cutting);
    }

    public function EvaluatedNbreMatter($str, $cutting) {
      list($classe, $matter) = explode('_', $str);
      return Evaluated::where('actif', '1')->where('get_classe_id', $classe)
      ->where('cutting_school_year_id', $cutting)->where('level_matter_id', $matter)
      ->count();
    }

    public function getNotEvaluat($student, $matter, $cutting){
      $data = DB::table('evaluats as e')
      ->join('evaluateds as ev', 'ev.id', '=', 'e.evaluated_id')
      ->where([
        'e.register_id' => $student, 
        'ev.level_matter_id' => $matter, 
        'cutting_school_year_id' => $cutting, 
        'ev.actif' => '1'
      ])
      ->select('ev.value', 'e.note')
      ->orderBy('ev.created')->get();
      return $data ?? [];
    }


    private function studentGet($evaluat) {
      return DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->leftJoin('evaluats as e', function ($join) use ($evaluat) {
        $join->on('e.register_id', '=', 'r.id')
        ->join('evaluateds as ev', 'ev.id', '=', 'e.evaluated_id')
        ->where('ev.id', $evaluat);
      })
      ->select(['ev.id', 'e.note', 'ev.value', 's.matricul', 's.first', 's.last', 's.genre'])
      ->orderByRaw('s.first, s.last')
      ->get();
    }


    private function getMoyenne($classe, $cutting, $matter) {
      
    }
  }