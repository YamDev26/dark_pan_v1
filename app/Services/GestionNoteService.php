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

    
    public function getNote($classe, $evaluat) {
      $query = $this->studentGet($classe, $evaluat);
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


    public function moyenneFrensh($classe, $matter, $cutting) {
      $query = $this->getMoyennefresh($classe, $matter, $cutting);
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
      ->addColumn('cf', function ($row) {
        return ($row->cf ? $row->cf:'--');
      })
      ->addColumn('og', function ($row) {
        return ($row->og ? $row->og:'--');
      })
      ->addColumn('eo', function ($row) {
        return ($row->eo ? $row->eo:'--');
      })
      ->addColumn('moyenne', function ($row) {
        return ($row->moyenne ? $row->moyenne:'--');
      })
      ->addColumn('rang', function ($row) {
        return ($row->rang ? $row->rang:'--');
      })
      ->rawColumns([
        'compte', 'matricule', 'first', 'last', 'genre', 'cf', 'og', 'eo', 'moyenne', 'rang'
      ])
      ->make(true);
    }


    public function getMoyenneMatterClasse($classe, $matter, $cutting) {
      $query = $this->getMoyenneMatter($classe, $matter, $cutting);
      $compte = 0;
      return DataTables::of($query)
      ->addColumn('compte', function() use (&$compte) {
        return ($compte < 9 ? '0'.++$compte : ++$compte);
      })
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


    public function getNotStudent($classe, $evaluat) {
      return $this->studentGet($classe, $evaluat);
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

    public function EvaluatedMatter($classe, $matter, $cutting) {
      return Evaluated::where('actif', '1')->where('get_classe_id', $classe)
      ->where('cutting_school_year_id', $cutting)->where('level_matter_id', $matter)
      ->get();
    }

    public function getNotEvaluat($student, $matter, $cutting, $sub = null) {
      $data = DB::table('evaluats as e')
      ->join('evaluateds as ev', 'ev.id', '=', 'e.evaluated_id')
      ->where([
        'e.register_id' => $student,
        'ev.sub_matter_id' => $sub, 
        'ev.level_matter_id' => $matter, 
        'cutting_school_year_id' => $cutting, 
        'ev.actif' => '1'
      ])
      ->select('ev.value', 'e.note')
      ->orderBy('ev.created')->get();
      return $data ?? [];
    }


    private function studentGet($classe, $evaluat) {
      return DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->leftJoin('evaluats as e', function ($join) use ($evaluat) {
        $join->on('e.register_id', '=', 'r.id')
        ->join('evaluateds as ev', 'ev.id', '=', 'e.evaluated_id')
        ->where('ev.id', $evaluat);
      })
      ->where('r.get_classe_id', $classe)
      ->select(['r.id', 'e.note', 'ev.value', 's.matricul', 's.first', 's.last', 's.genre'])
      ->orderByRaw('s.first, s.last')
      ->get();
    }


    private function getMoyennefresh($classe, $matter, $cutting) {
      return DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->leftJoin('moyenne_matters as mm', function ($join) use ($cutting, $matter) {
        $join->on('mm.register_id', '=', 'r.id')
        ->where('mm.cutting_school_year_id', $cutting)
        ->where('mm.level_matter_id', $matter);
      })
      ->leftJoin('moyenne_sub_matters as msm', function ($join) use ($cutting, $matter) {
        $join->on('msm.register_id', '=', 'r.id')
        ->where('msm.cutting_school_year_id', $cutting);
      })
      ->where('r.get_classe_id', $classe)
      ->groupBy('r.id', 's.matricul', 's.first', 's.last', 's.genre', 'mm.moyenne', 'mm.rang')
      ->select('r.id', 's.matricul', 's.first', 's.last', 's.genre', 'mm.moyenne', 'mm.rang',
        DB::raw('MAX(CASE WHEN msm.sub_matter_id = 1 THEN msm.moyenne END) as cf'),
        DB::raw('MAX(CASE WHEN msm.sub_matter_id = 2 THEN msm.moyenne END) as og'),
        DB::raw('MAX(CASE WHEN msm.sub_matter_id = 3 THEN msm.moyenne END) as eo')
      )->get();
    }


    private function getEvaluadtedMatters($classe, $matter, $cutting) {
      return DB::table('evaluats as e')
      ->join('registers as r', 'r.id', '=', 'e.register_id')
      ->leftJoin('evaluateds as es', 'es.id', '=', 'e.evaluated_id' )
      ->where([
        'es.cutting_school_year_id' => $cutting,
        'es.level_matter_id' => $matter,
        'r.get_classe_id' => $classe,
        'es.actif' => '1'
      ])
      ->select(
        'e.register_id',
        'e.note'
      )
      ->orderBy('es.created', 'ASC')
      ->get()
      ->groupBy('register_id');
    }


    private function getMoyenneMatter($classe, $matter, $cutting) {
      $students = DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->leftJoin('moyenne_matters as mm', function ($join) use ($cutting, $matter) {
        $join->on('mm.register_id', '=', 'r.id')
        ->where([
          'mm.cutting_school_year_id' => $cutting,
          'mm.level_matter_id' => $matter
        ]);
      })
      ->where('r.get_classe_id', $classe)
      ->select('r.id as register_id', 's.matricul', 's.first', 's.last', 's.genre', 'mm.moyenne', 'mm.rang')
      ->get();

      $notes = $this->getEvaluadtedMatters($classe, $matter, $cutting);
      return $students->map(function ($item) use ($notes) {
        $row = [
          'register_id' => $item->register_id,
          'matricul'    => $item->matricul,
          'first'       => strtoupper($item->first),
          'last'        => ucwords($item->last),
          'genre'       => $item->genre == 'F' ? 'Feminin':'Masculin',
          'moyenne'     => $item->moyenne ?? '--',
          'rang'        => $item->rang ?? '--'
        ];
        foreach ($notes[$item->register_id] ?? [] as $i => $sub) {
          $row['N_'.$i+1] = $sub->note;
        }
        return $row;
      });
    }
  }