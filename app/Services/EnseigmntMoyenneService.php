<?php
  namespace App\Services;

  use App\Models\GetClasse;
  use App\Models\SchoolYear;
  use App\Models\LevelMatter;
  use App\Models\CuttingSchoolYear;
  use App\Models\CuttingCloseSchool;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;
  use Yajra\DataTables\Facades\DataTables;
  
  class EnseigmntMoyenneService
  {
    private $user;

    public function __construct() {
      $this->user = Auth::user();
    }
    

    public function getClasse($str) {
      return GetClasse::find($str);
    }

    public function getCutting($str) {
      return CuttingSchoolYear::find($str);
    }

    public function getMatter($str) {
      return LevelMatter::find($str);
    }

    public function getCloseCutting($cutting) {
      $verify = CuttingCloseSchool::where('school_id', $this->user->school_id)
      ->where('cutting_school_year_id', $cutting)->first();
      return $verify ? true:false;
    }

    public function verifyClasse($classe, $matter) {
      $data = DB::table('classe_teachers as ct')
      ->join('get_classes as gc', 'gc.id', '=', 'ct.get_classe_id')
      ->join('level_matters as lm', 'lm.id', '=', 'ct.level_matter_id')
      ->join('matters as m', 'm.id', '=', 'lm.matter_id')
      ->where([
        'gc.school_year_id' => $this->year(),
        'gc.school_id' => $this->user->school_id,
        'ct.user_id' => $this->user->id,
        'ct.level_matter_id' => $matter,
        'ct.get_classe_id' => $classe
      ])
      ->first();
      return $data ? true:false;
    }
    
    public function getClasseUsers() {
      $compte = 0;
      return DataTables::of($this->getClasseElement())
      ->addColumn('compte', function() use (&$compte) {
        return (sprintf('%02d', $compte += 1));
      })
      ->addColumn('libelle', function ($row) {
        return ($row->libelle);
      })
      ->addColumn('effectif', function ($row) {
        return (sprintf('%02d', $row->inscrit).' / '.sprintf('%02d', $row->effectif));
      })
      ->addColumn('matters', function ($row) {
        return (ucwords($row->symbol));
      })
      ->addColumn('action', function ($row) {
        $url = route('evaluation.show',($row->classe.'_'.$row->matter));
        return (
          '<button class="btn btn-sm btn-light dropdown-toggle py-0" type="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-ellipsis-h"></i>
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="min-width: 6rem;">
            '.$this->listCutting($row->classe, $row->matter).'
          </ul>'
        );
      })
      ->rawColumns(['compte', 'libelle', 'effectif', 'matters', 'action'])
      ->make(true);
    }


    public function getMoyenneMatters($classe, $matter, $cutting) {
      $query = $this->getStudentMoyenneMatter($classe, $matter, $cutting);
      $compte = 0;
      return DataTables::of($query)
      ->addColumn('compte', function() use (&$compte) {
        return (sprintf('%02d', $compte += 1));
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


    public function getMoyenneFrenshs($classe, $matter, $cutting) {
      $query = $this->getMoyennefresh($classe, $matter, $cutting);
      $compte = 0;
      return DataTables::of($query)
      ->addColumn('compte', function() use (&$compte) {
        return (sprintf('%02d', $compte += 1));
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


    public function getStudentMoyenneMatter($classe, $matter, $cutting) {
      return DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->leftJoin('moyenne_matters as mm', function ($join) use ($matter, $cutting) {
        $join->on('mm.register_id', '=', 'r.id')
        ->where('mm.level_matter_id', $matter)
        ->where('mm.cutting_school_year_id', $cutting);
      }) 
      ->select([ 
        'r.id', 's.matricul', 's.first', 's.last', 's.genre', 'mm.moyenne', 'mm.rang',
      ])
      ->where('r.get_classe_id', $classe)
      ->orderByRaw('s.first, s.last')
      ->get();
    }
    

    public function getMoyennefresh($classe, $matter, $cutting) {
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

    public function getCoduiteMoyenne($classe, $matter, $cutting) {
      return DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->leftJoin('moyenne_matters as mm', function ($join) use ($matter, $cutting) {
        $join->on('mm.register_id', '=', 'r.id')
        ->where('mm.level_matter_id', $matter)
        ->where('mm.cutting_school_year_id', $cutting);
      })
      ->leftJoin('absences as a', function ($join) use ($matter, $cutting) {
        $join->on('a.register_id', '=', 'r.id')
        ->where('a.cutting_school_year_id', $cutting);
      }) 
      ->select([ 
        'r.id', 's.matricul','s.first','s.last', 's.genre', 'mm.moyenne', 'mm.rang',
        'a.absens1 as justify', 'a.absens1 as noJustify'
      ])
      ->where('r.get_classe_id', $classe)
      ->orderByRaw('s.first, s.last')
      ->get();
    }


    private function getClasseElement() {
      return DB::table('classe_teachers as ct')
      ->join('get_classes as gc', 'gc.id', '=', 'ct.get_classe_id')
      ->join('level_matters as lm', 'lm.id', '=', 'ct.level_matter_id')
      ->join('matters as m', 'm.id', '=', 'lm.matter_id')
      ->where([
        'gc.school_year_id' => $this->year(),
        'gc.school_id' => $this->user->school_id,
        'ct.user_id' => $this->user->id
      ])
      ->select([
        'gc.id as classe', 'lm.id as matter', 'gc.libelle', 'gc.inscrit', 'gc.effectif', 'm.symbol'
      ])
      ->orderBy('gc.level_id')
      ->get();
    }

    private function listCutting($classe, $matter) {
      return CuttingSchoolYear::with('cutting')
      ->where('school_year_id', $this->year())
      ->get()
      ->map(function ($item) use ($classe, $matter) {
        $url = route('moyennes.show', $classe.'_'.$matter.'_'.$item->id);
        return '
          <li><a href="'.$url.'" class="dropdown-item">' . ucwords($item->cutting->libelle) . '</a></li>
        ';
      })->implode('');
    }

    private function year() {
      $year = SchoolYear::where('status', '1')->first();
      return $year ? $year->id:null;
    }
  }