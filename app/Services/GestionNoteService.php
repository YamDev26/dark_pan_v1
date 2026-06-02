<?php
  namespace App\Services;

  use App\Models\School;
  use App\Models\Evaluated;
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
      $query = DB::table('registers as r')
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
        return (($row->note ? $row->note:'--').' / '.($row->value ?? '--'));
      })
      ->rawColumns(['compte', 'matricule', 'first', 'last', 'genre', 'note'])
      ->make(true);
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
    

    public function evaluated($str) {
      return Evaluated::find($str);
    }

    private function school() {
      return School::find($this->schl) ?? null;
    }
  }