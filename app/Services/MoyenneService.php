<?php
  namespace App\Services;

  use App\MOdels\School;
  use App\Models\GetClasse;
  use App\Models\SchoolYear;
  use App\Models\LevelMatter;
  use App\Models\MoyenneBilan;
  use App\Models\MoyenneMatter;
  use App\Models\MoyenneSubMatter;
  use App\Models\MoyenneTrimestre;
  use App\Models\CuttingSchoolYear;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;
  use Yajra\DataTables\Facades\DataTables;
  class MoyenneService
  {
    private const S_LEVEL_ID  = 5;
    private $schl; private const A_ACTIF  = 1;
    
    public function __construct() {
      $this->schl = Auth::user()->school_id ?? 1;
    }

    public function school() {
      return School::find($this->schl) ?? null;
    }
    
    public function getDataTableClasse() {
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
          '<select class="w-auto border-0 text-color-3" onchange="window.location.href=this.value;" style="background:none; color: #6C7293">
            <option value="">...</option>
            '.$this->listCutting($row->id).'
          </select>'
        );
      })
      ->rawColumns(['compte', 'libelle', 'effectif', 'action'])
      ->make(true);
    }


    public function getMoyenneMCuttingClasse($level, $classe,  $cutting, $serie = null) {
      $query = $this->getMoyenneCutting($level, $classe,  $cutting, $serie);
      $compte = 0;
      return DataTables::of($query)
      ->addColumn('compte', function() use (&$compte) {
        return ($compte < 9 ? '0'.++$compte : ++$compte);
      })
      ->make(true);
    }

    public function getStudentMoyenne($classe, $matter, $cutting) {
      return $this->getStudentMoyenMatter($classe, $matter, $cutting);
    }


    public function getStudentMoyenneFrensh($classe, $matter, $cutting) {
      return $this->getMoyennefresh($classe, $matter, $cutting);
    }

    public function getListMoyenneMatter($classe, $matter, $cutting) {
      $query = $this->getStudentMoyenMatter($classe, $matter, $cutting);
      return $this->tableYajra2($query);
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

    public function getSubMatter() {
      $data = DB::table('sub_matters as sm')
      ->select('sm.id','sm.libelle','sm.symbol' )
      ->orderBy('sm.id')
      ->get();
      return json_decode($data, true);
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

    public function saveMoyenneMatter($student, $moyenne, $matter, $cutting, $rang = null) {
      MoyenneMatter::updateOrCreate([
          'register_id' => $student,
          'level_matter_id' => $matter,
          'cutting_school_year_id' => $cutting,
        ], [
          'moyenne' => $moyenne,
          'rang' => $rang ?? '--'
        ]
      );
    }


    public function moyenneSubMatter($student, $moyenne, $matter, $cutting, $value, $rang = null) {
      MoyenneSubMatter::updateOrCreate([
          'register_id' => $student,
          'sub_matter_id' => $matter,
          'cutting_school_year_id' => $cutting,
        ], [
          'moyenne' => $moyenne,
          'values' => $value,
          'rang' => $rang ?? '--'
        ]
      );
    }

    public function getMoyenneSubMatter($student, $cutting) {
      $dts = MoyenneSubMatter::where('register_id', $student)
      ->where('cutting_school_year_id', $cutting)->get();
      return $dts ?? [];
    }

    public function saveMoyenneBilanMatter($student, $moyenne, $value, $bilan, $cutting, $rang = null) {
      MoyenneBilan::updateOrCreate([
          'register_id' => $student,
          'bilan_matter_id' => $bilan,
          'cutting_school_year_id' => $cutting,
        ], [
          'moyenne' => $moyenne,
          'rang' => $rang ?? '--',
          'values' => $value ?? 'nc'
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


    public function moyenneTrimestreClasseStudent($classe, $cutting) {
      return $this->getMoyenneTrimestreClasseStudent($classe, $cutting);
    }


    public function frenshIdGet($level) {
      return DB::table('level_matters')
      ->where('school_id', $this->schl)
      ->where('level_id', $level)
      ->where('matter_id', 2)
      ->value('id');
    }


    public function updateMoyenne($item, $cutting, $level) {
      MoyenneTrimestre::where('register_id', $item)
      ->where('cutting_school_year_id', $cutting)
      ->update([
        'moyenne' => 'nc',
        'rang' => 'nc',
      ]);

      $this->updateMoyenneBilan($item, $cutting, $level);
    }


    private function updateMoyenneBilan($item, $cutting, $level) {
      MoyenneBilan::where('register_id', $item)
      ->where('cutting_school_year_id', $cutting)
      ->update([
        'moyenne' => 'nc',
        'rang' => 'nc',
      ]);

      $this->updateMoyenneMatter($item, $cutting, $level);
    }

    private function updateMoyenneMatter($item, $cutting, $level) {
      MoyenneMatter::where('register_id', $item)
      ->where('cutting_school_year_id', $cutting)
      ->update([
        'moyenne' => 'nc',
        'rang' => 'nc',
      ]);

      if($level < self::S_LEVEL_ID) {
        $this->updateMoyenneSubMatter($item, $cutting);
      }
    }


    private function updateMoyenneSubMatter($item, $cutting) {
      MoyenneSubMatter::where('register_id', $item)
      ->where('cutting_school_year_id', $cutting)
      ->update([
        'moyenne' => 'nc',
        'rang' => 'nc',
      ]);
    }


    public function getMoyenneCutting($level, $classe, $cutting, $serie = null) {

      $students = $this->getMoyenneTrimestreClasseStudent($classe, $cutting);
      $matters = $this->getMoyenneMatters($level, $classe, $cutting, $serie);

      return $students->map(function ($item) use ($matters) {
        $row = [
          'register_id' => $item->register_id,
          'matricul'    => $item->matricul,
          'name'        => strtoupper($item->first).' '.ucwords($item->last),
          'genre'       => $item->genre,
          'moyenne'     => $item->moyenne ?? '--',
          'rang'        => $item->rang ?? '--'
        ];
        foreach ($matters[$item->register_id] ?? [] as $sub) {
          $row[$sub->symbol] = $sub->moyenne;
        }
        return $row;
      });
    }



    private function listCutting($classe) {
      return CuttingSchoolYear::with('cutting')
      ->where('school_year_id', $this->year())
      ->get()
      ->map(function ($item) use ($classe) {
        $url = route('moyenne.show', $classe . '_' . $item->id);
        return '
          <option value="'.$url.'">' . ucwords($item->cutting->libelle) . '</option>
        ';
      })->implode('');
    }

    private function year() {
      $year = SchoolYear::where('status', (string)self::A_ACTIF)->first();
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

    private function getSubMattersQuery($level, $classe, $cutting) {
      return DB::table('registers as r')
      ->join('level_matters as lm', function ($join) use ($level) {
        $join->where('lm.level_id', $level)
        ->where('lm.matter_id', 2);
      })
      ->join('sub_matters as sm', 'sm.matter_id', '=', 'lm.matter_id')
      ->leftJoin('moyenne_sub_matters as mm', function ($join) use ($cutting) {
        $join->on('mm.register_id', '=', 'r.id')
        ->on('mm.sub_matter_id', '=', 'sm.id')
        ->where('mm.cutting_school_year_id', $cutting);
      })
      ->where('r.get_classe_id', $classe)
      ->select(
        'r.id as register_id',
        'sm.libelle',
        'sm.symbol',
        'mm.moyenne'
      );
    }


    private function SubMatters($level, $classe,  $cutting) {
      return $this->getSubMattersQuery($level, $classe, $cutting)
      ->orderBy('r.id')
      ->orderBy('sm.id')
      ->get()
      ->groupBy('register_id')
      ->map(fn ($items) => $items->values()->toArray())
      ->toArray();
    }


    private function getMoyenneMatters($level, $classe, $cutting, $serie = null) {
      $matters = DB::table('registers as r')
      ->join('level_matters as lm', function ($join) use ($level, $serie) {
        $join->where('lm.level_id', $level)
        ->where('lm.serie_id', $serie);
      })
      ->join('matters as m', 'm.id', '=', 'lm.matter_id')
      ->leftJoin('moyenne_matters as mm', function ($join) use ($cutting) {
        $join->on('mm.register_id', '=', 'r.id')
        ->on('mm.level_matter_id', '=', 'lm.id')
        ->where('mm.cutting_school_year_id', $cutting);
      })
      ->where('r.get_classe_id', $classe)
      ->select(
        'r.id as register_id',
        'm.libelle',
        'm.symbol',
        'mm.moyenne'
      )
      ->orderBy('r.id')
      ->orderBy('m.bilan_matter_id')
      ->orderBy('m.position')
      ->get()
      ->groupBy('register_id')
      ->map(fn ($items) => $items->values()->toArray())
      ->toArray();

      if ($level >= self::S_LEVEL_ID) {
        return $matters;
      }
      foreach ($this->SubMatters($level, $classe, $cutting) as $registerId => $subs) {
        $matters[$registerId] = array_merge(
          $subs,
          $matters[$registerId] ?? []
        );
      }
      return $matters;
    }


    private function getMoyenneTrimestreClasseStudent($classe, $cutting) {
      return DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->leftJoin('moyenne_trimestres as mt', function ($join) use ($cutting) {
        $join->on('mt.register_id', '=', 'r.id')
        ->where('mt.cutting_school_year_id', $cutting);
      })
      ->where('r.get_classe_id', $classe)
      ->select('r.id as register_id', 's.matricul', 's.first', 's.last', 's.genre', 'mt.moyenne', 'mt.rang')
      ->orderBy('s.first')->orderBy('s.last')
      ->get();
    }

  }