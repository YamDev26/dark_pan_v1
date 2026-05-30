<?php
  namespace App\Services;

  use App\Models\GetClasse;
  use App\Models\SchoolYear;
  use App\Models\LevelMatter;
  use App\Models\MoyenneBilan;
  use App\Models\MoyenneMatter;
  use App\Models\MoyenneTrimestre;
  use App\Models\CuttingSchoolYear;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;
  use Yajra\DataTables\Facades\DataTables;
  class MoyenneService
  {
    private $schl;
    public function __construct() {
      $this->schl = Auth::user()->school_id ?? 1;
    }
    
    public function getYajra() {
      $query = GetClasse::where('school_id', $this->schl)
      ->where('school_year_id', $this->year())->where('status', '1')
      ->orderBy('level_id')->orderBy('serie_id')->get();
      $compte = 0;
      return DataTables::of($query)
      ->addColumn('compte', function() use (&$compte) {
        return ($compte < 9 ? '0'.++$compte : ++$compte);
      })
      ->addColumn('libelle', function ($row) {
        return ($row->libelle);
      })
      ->addColumn('effectif', function ($row) {
        return (($row->inscrit < 10 ? '0'.$row->inscrit:$row->inscrit).' / '.$row->effectif);
      })
      ->addColumn('action', function ($row) {
        return (
          '<a class="btn btn-sm btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-ellipsis-h"></i>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="min-width: 8rem;">'
            .$this->cutting($row->id).
          '</ul>'
        );
      })
      ->rawColumns(['compte', 'libelle', 'effectif', 'action'])
      ->make(true);
    }


    public function getYajra_2($classe, $cutting, $matter) {
      $query = $this->getStudentMoyenMatter($classe, $matter, $cutting);
      return $this->tableYajra2($query);
    }

    public function getYajra_3($classe, $cutting, $matter) {
      $query = $this->getStudentMoyenMatter($classe, $matter, $cutting);
      return $this->tableYajra3($query);
    }

    public function getClasse($str) {
      return GetClasse::find($str) ?? null;
    }

    public function getCutting($str) {
      return CuttingSchoolYear::find($str) ?? null;
    }

    public function getMatter($str) {
      return LevelMatter::find($str) ?? null;
    }


    public function getMatters($str) {
      $class = $this->getClasse($str);
      if (!$class) {
        return collect();
      }
      return DB::table('level_matters as lm')
      ->join('matters as m', 'm.id', '=', 'lm.matter_id')
      ->where([
        'lm.level_id' => $class['level_id'],
        'lm.serie_id' => $class['serie_id'],
      ])
      ->select('lm.id','lm.value','m.libelle','m.symbol' )
      ->orderByRaw('m.bilan_matter_id, m.position')
      ->get();
    }

    public function getStudent($str) {
      $dts = DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->select(['r.id', 's.matricul','s.first', 's.last', 's.genre', 's.date'])
      ->where('r.get_classe_id', $str)->orderBy('s.first')->orderBy('s.last')->get();
      return $dts ?? [];
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

    public function getBilanMatter($matter) {
      $bilan = LevelMatter::find($matter);
      return $bilan ? $bilan->matter->bilan_matter_id:null;
    }

    public function sumMoyenneMatterBilan($student, $cutting, $bilan) {
      return DB::table('moyenne_matters as mm')
      ->join('level_matters as lm', 'lm.id', '=', 'mm.level_matter_id')
      ->join('matters as m', 'm.id', '=', 'lm.matter_id')
      ->where('mm.cutting_school_year_id', $cutting)
      ->where('m.bilan_matter_id', $bilan)
      ->where('mm.register_id', $student)
      ->where('mm.moyenne', '!=', 'nc')
      ->selectRaw('
        SUM(mm.moyenne * lm.value) as total,
        SUM(lm.value) as value
      ')->first();
    }

    public function sumMoyenneMatter($student, $cutting) {
      return DB::table('moyenne_matters as mm')
      ->join('level_matters as lm', 'lm.id', '=', 'mm.level_matter_id')
      ->where('mm.cutting_school_year_id', $cutting)
      ->where('mm.register_id', $student)
      ->where('mm.moyenne', '!=', 'nc')
      ->selectRaw('
        SUM(mm.moyenne * lm.value) as total,
        SUM(lm.value) as value
      ')->first();
    }

    public function saveMoyenneMatter($student, $moyenne, $rang, $matter, $cutting) {
      MoyenneMatter::updateOrCreate([
          'register_id' => $student,
          'level_matter_id' => $matter,
          'cutting_school_year_id' => $cutting,
        ], [
          'moyenne' => $moyenne,
          'rang' => $rang
        ]
      );
    }

    public function saveMoyenneBilanMatter($student, $moyenne, $rang, $value, $bilan, $cutting) {
      MoyenneBilan::updateOrCreate([
          'register_id' => $student,
          'bilan_matter_id' => $bilan,
          'cutting_school_year_id' => $cutting,
        ], [
          'moyenne' => $moyenne,
          'rang' => $rang,
          'values' => $value
        ]
      );
    }

    public function saveMoyenneTrimestre($student, $moyenne, $rang, $value, $cutting) {
      MoyenneTrimestre::updateOrCreate([
          'register_id' => $student,
          'cutting_school_year_id' => $cutting,
        ], [
          'moyenne' => $moyenne,
          'rang' => $rang,
          'values' => $value
        ]
      );
    }

    public function getResultat($classe, $cutting) {
      $column = $this->matieres($classe);
      $query = DB::table('registers as r')
        ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
        ->join('students as s', 's.id', '=', 'ss.student_id')

        ->leftJoin('moyenne_matters as mm', function ($join) use ($cutting) {
          $join->on('mm.register_id', '=', 'r.id')
          ->join('level_matters as lm', 'lm.id', '=', 'mm.level_matter_id')
          ->join('matters as m', 'm.id', '=', 'lm.matter_id')
          ->where('mm.cutting_school_year_id', $cutting);
        })
        ->leftJoin('moyenne_trimestres as mt', function ($join) use ($cutting) {
          $join->on('mt.register_id', '=', 'r.id')
          ->where('mt.cutting_school_year_id', $cutting);
        })
        ->where(['r.get_classe_id' => $classe])
        ->orderByRaw('s.first, s.last')
        ->selectRaw("
          r.id,
          s.matricul,
          s.first,
          s.last,
          ".implode(',', $column).",
          mt.moyenne AS moyenne_trim,
          mt.rang AS rang_trim
        ")
        ->groupBy(
          'r.id',
          's.matricul',
          's.first',
          's.last',
          'mt.moyenne',
          'mt.rang'
        )
        ->get();

      return $this->tableYajraResultat($query);
    }

    public function matieres($str) {
      $dts = $this->getMatters($str);
      $columns = [];
      foreach ($dts as $matiere) {
      $alias = $matiere->symbol;
        $columns[] = "
          MAX(
            CASE
              WHEN mm.level_matter_id = {$matiere->id}
              THEN mm.moyenne
            END
          ) AS `$alias`
        ";
      }
      return $columns;
    }

    private function cutting($classe) {
      return CuttingSchoolYear::with('cutting')
      ->where('school_year_id', $this->year())
      ->get()
      ->map(function ($item) use ($classe) {
        $url = route('moyenne.show', $classe . '_' . $item->id);
        return '
          <li>
            <a class="dropdown-item" href="' . $url . '">
              ' . ucwords($item->cutting->libelle) . '
            </a>
          </li>
        ';
      })->implode('');
    }

    private function year() {
      $year = SchoolYear::where('status', '1')->first();
      return $year ? $year->id:null;
    }

    private function tableYajra2($query) {
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
      ->addColumn('moyenne', function ($row) {
        return ($row->moyenne ?? '---');
      })
      ->addColumn('rang', function ($row) {
        return ($row->rang ?? '---');
      })
      ->rawColumns(['compte', 'matricule', 'first', 'last', 'genre', 'moyenne', 'rang'])
      ->make(true);
    }

    private function tableYajra3($query) {
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
      ->addColumn('input', function ($row) {
        return (
          '<input type="hidden" name="str[]" value="'.$row->id.'_'.$row->genre.'">
          <input type="text" name="moyen[]" class="form-control mx-0" value="'.($row->moyenne).'" placeholder="---">'
        );
      })
      ->rawColumns(['compte', 'matricule', 'first', 'last', 'genre', 'input'])
      ->make(true);
    }

    private function tableYajraResultat($query, $column) {
      $compte = 0;
      return DataTables::of($query)
      ->addIndexColumn()
      ->addColumn('compte', function() use (&$compte) {
        return ($compte < 9 ? '0'.++$compte : ++$compte);
      })
      ->addColumn('matricule', function ($row) {
        return ($row->matricul);
      })
      ->addColumn('name', function ($row) {
        return (strtoupper($row->first).' '.ucwords($row->last));
      })
      ->addColumn('moyene', function ($row) {
        return ($row->moyenne_trim);
      })
      ->addColumn('rang', function ($row) {
        return ($row->rang_trim);
      })
      ->make(true);
    }

    private function getStudentMoyenMatter($classe, $matter, $cutting) {
      return DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->leftJoin('moyenne_matters as mm', function ($join) use ($matter, $cutting) {
        $join->on('mm.register_id', '=', 'r.id')
        ->where('mm.level_matter_id', $matter)
        ->where('mm.cutting_school_year_id', $cutting);
      }) 
      ->select([ 
        'r.id', 's.matricul','s.first','s.last', 's.genre','mm.moyenne', 'mm.rang',
      ])
      ->where('r.get_classe_id', $classe)
      ->orderByRaw('s.first, s.last')
      ->get();
    }

  }