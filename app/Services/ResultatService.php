<?php
  namespace App\Services;

  use App\Models\School;
  use App\Models\Resultat;
  use App\Models\GetClasse;
  use App\Models\SchoolYear;
  use App\Models\MatterResultat;
  use App\Models\TrancheMoyenne;
  use App\Models\CuttingSchoolYear;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;
  use Yajra\DataTables\Facades\DataTables;

  class ResultatService
  {
    private $schl; private const A_ACTIF  = 1;


    public function __construct() {
      $this->schl = Auth::user()->school_id ?? 1;
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


    public function getCuttings() {
      return CuttingSchoolYear::with('cutting')
      ->where('school_year_id', $this->year())
      ->get();
    }

    public function getClasse($str) {
      return GetClasse::find($str) ?? null;
    }


    public function getCutting($str) {
      return CuttingSchoolYear::find($str) ?? null;
    }


    public function getResultatClasse($classe, $cutting) {
      return Resultat::where('get_classe_id', $classe)
      ->where('cutting_school_year_id', $cutting)
      ->first();
    }


    public function getResultatTranche($classe, $cutting) {
      return TrancheMoyenne::where('get_classe_id', $classe)
      ->where('cutting_school_year_id', $cutting)
      ->first();
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


    public function resultatMatter($classe, $cutting) {
      $class = $this->getClasse($classe);
      if (!$class) {
        return collect();
      }
      return DB::table('level_matters as lm')
      ->join('matters as m', 'm.id', '=', 'lm.matter_id')
      ->leftJoin('matter_resultats as mr', function ($join) use ($cutting, $classe) {
        $join->on('mr.level_matter_id', '=', 'lm.id')
        ->where([
          'mr.cutting_school_year_id' => $cutting,
          'mr.get_classe_id' => $classe
        ]);
      })
      ->where([
        'lm.level_id' => $class['level_id'],
        'lm.serie_id' => $class['serie_id'],
      ])
      ->select('mr.valeur', 'm.symbol')
      ->orderByRaw('m.bilan_matter_id, m.position')
      ->get();
    }


    public function tauxReussiteMatterSave($matter, $cutting, $classe, $valuer) {
      MatterResultat::updateOrCreate([
          'get_classe_id' => $classe,
          'level_matter_id' => $matter,
          'cutting_school_year_id' => $cutting,
        ], [
          'valeur' => $valuer
        ]
      );
    }


    public function resultatClasseSave($classe, $cutting, $moyenn, $effectif = null, $taux = null, $tauxF = null, $tauxM = null, $dificulity = null) {

      $result = $this->maxMinMoyenne($classe, $cutting,);
      Resultat::updateOrCreate([
          'get_classe_id' => $classe,
          'cutting_school_year_id' => $cutting,
        ], [
          'effectif' => $effectif,
          'moyenne' => $moyenn,
          'reussite' => $taux,
          'taux_f' => $tauxF,
          'taux_m' => $tauxM,
          'dificulte' => $dificulity,
          'max' => $result->max,
          'min' => $result->min
        ]
      );
    }


    public function TrancheMoyenneSavec($classe, $cutting) {

      $result = $this->nombreStudentObtenuMoyenne($classe, $cutting);
      TrancheMoyenne::updateOrCreate([
          'get_classe_id' => $classe,
          'cutting_school_year_id' => $cutting,
        ], [
          'moyenne_0_849' => $result->moyenne_0_849,
          'moyenne_850_999' => $result->moyenne_850_999,
          'moyenne_10_1199' => $result->moyenne_10_1199,
          'moyenne_12_1399' => $result->moyenne_12_1399,
          'moyenne_14_1599' => $result->moyenne_14_1599,
          'moyenne_16_plus' => $result->moyenne_16_plus,
        ]
      );
    }


    public function tauxReussiteMatter($matter, $classe, $cutting) {
      return DB::table('moyenne_matters as mm')
      ->join('registers as r', 'r.id', '=', 'mm.register_id')
      ->where([
        'mm.cutting_school_year_id' => $cutting,
        'mm.level_matter_id' => $matter,
        'r.get_classe_id' => $classe
      ])
      ->where('mm.moyenne', '<>', 'nc')
      ->selectRaw("
        COUNT(*) AS effectif,
        SUM(CASE WHEN mm.moyenne >= 10 THEN 1 ELSE 0 END) AS admis,
        ROUND(
          COALESCE(
            SUM(CASE WHEN mm.moyenne >= 10 THEN 1 ELSE 0 END) * 100 / NULLIF(COUNT(*), 0),
            0
          ),
          2
        ) AS resultat
      ")
      ->first();
    }


    public function statistiquesClasseGenre($classe, $cutting) {
      return DB::table('moyenne_trimestres as mt')
      ->join('registers as r', 'r.id', '=', 'mt.register_id')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->where([
        ['mt.cutting_school_year_id', $cutting],
        ['r.get_classe_id', $classe],
      ])
      ->where('mt.moyenne', '<>', 'nc')
      ->groupBy('s.genre')
      ->selectRaw("
        s.genre,
        ROUND(
          SUM(CASE WHEN mt.moyenne >= 10 THEN 1 ELSE 0 END) * 100
          / NULLIF(COUNT(*), 0),
          2
        ) AS taux
      ")
      ->get();
    }


    public function statistiquesClasse($classe, $cutting) {
      return DB::table('moyenne_trimestres as mt')
      ->join('registers as r', 'r.id', '=', 'mt.register_id')
      ->where([
        ['mt.cutting_school_year_id', $cutting],
        ['r.get_classe_id', $classe],
      ])
      ->where('mt.moyenne', '<>', 'nc')
      ->selectRaw("
        COUNT(*) AS effectif,
        ROUND(AVG(mt.moyenne), 2) AS moyenne,
        ROUND(
          COALESCE(
            SUM(CASE WHEN mt.moyenne >= 10 THEN 1 ELSE 0 END) * 100
            / NULLIF(COUNT(*), 0),
            0
          ),
          2
        ) AS taux,
        SUM(CASE WHEN mt.moyenne < 8.50 THEN 1 ELSE 0 END) AS moins_de_850
      ")
      ->first();
    }


    private function nombreStudentObtenuMoyenne($classe, $cutting) {
      return DB::table('moyenne_trimestres as mt')
      ->join('registers as r', 'r.id', '=', 'mt.register_id')
      ->where([
        ['mt.cutting_school_year_id', $cutting],
        ['r.get_classe_id', $classe],
      ])
      ->where('mt.moyenne', '<>', 'nc')
      ->selectRaw("
        COUNT(*) AS effectif,
        SUM(CASE WHEN mt.moyenne BETWEEN 0 AND 8.49 THEN 1 ELSE 0 END) AS moyenne_0_849,
        SUM(CASE WHEN mt.moyenne BETWEEN 8.50 AND 9.99 THEN 1 ELSE 0 END) AS moyenne_850_999,
        SUM(CASE WHEN mt.moyenne BETWEEN 10 AND 11.99 THEN 1 ELSE 0 END) AS moyenne_10_1199,
        SUM(CASE WHEN mt.moyenne BETWEEN 12 AND 13.99 THEN 1 ELSE 0 END) AS moyenne_12_1399,
        SUM(CASE WHEN mt.moyenne BETWEEN 14 AND 15.99 THEN 1 ELSE 0 END) AS moyenne_14_1599,
        SUM(CASE WHEN mt.moyenne >= 16 THEN 1 ELSE 0 END) AS moyenne_16_plus
      ")
      ->first();
    }


    private function maxMinMoyenne($classe, $cutting) {
      return DB::table('moyenne_trimestres as mt')
      ->join('registers as r', 'r.id', '=', 'mt.register_id')
      ->where([
        ['mt.cutting_school_year_id', $cutting],
        ['r.get_classe_id', $classe],
      ])
      ->selectRaw('MAX(mt.moyenne) as max, MIN(mt.moyenne) as min')
      ->first();
    }


    private function listCutting($classe) {
      return CuttingSchoolYear::with('cutting')
      ->where('school_year_id', $this->year())
      ->get()
      ->map(function ($item) use ($classe) {
        $url = route('resultat.show', $classe. '_' .$item->id);
        return '
          <option value="'.$url.'">' . ucwords($item->cutting->libelle) . '</option>
        ';
      })->implode('');
    }


    private function year() {
      $year = SchoolYear::where('status', (string)self::A_ACTIF)->first();
      return $year ? $year->id:null;
    }

    private function school() {
      return School::find($this->schl) ?? null;
    }
  }