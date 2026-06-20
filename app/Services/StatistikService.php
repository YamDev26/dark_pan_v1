<?php
  namespace App\Services;

  use App\Models\School;
  use App\Models\Statistik;
  use App\Models\GetClasse;
  use App\Models\StatistikSerie;
  use App\Models\ResultatScolaire;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;
  
  class StatistikService
  {
    private $schl;

    public function __construct() {
      $this->schl = Auth::user()->school_id ?? 1;
    }


    public function getclasse($str) {
      return GetClasse::find($str);
    }
    

    public function getStatistik($cutting) {

      $school = $this->school();
      return DB::table('levels as l')
      ->leftJoin('statistiks as s', function ($join) use ($cutting) {
        $join->on('s.level_id', '=', 'l.id')
        ->where([
          'cutting_school_year_id' => $cutting,
          'school_id' => $this->schl,
        ]);
      })
      ->where(function ($query) use ($school) {
        $query->where('l.cycle1', $school['cycle1'])
        ->orWhere('l.cycle2', $school['cycle2']);
      })
      ->select([
        'l.symbol', 's.effectif_total', 's.effectif_garcon', 's.effectif_fille',
        's.classified', 's.unranked', 's.admis', 's.admis_garcon', 's.admis_fille',
        's.taux_total', 's.taux_garcon', 's.taux_fille'
      ])
      ->orderBy('l.id')->get();
    }


    public function getResultat($cutting) {
      return ResultatScolaire::where('school_id', $this->schl)
      ->where('cutting_school_year_id', $cutting)->first();
    }


    public function getResultatSerie($cutting) {
      return $this->resultatQuery($cutting)
        ->groupBy('symbol')
        ->map(function ($items) {

            $first = $items->first();

            return [
                'niveau' => $first->symbol,
                'series' => $items->map(function ($item) {
                    return [
                        'serie'     => $item->libelle,
                        'nbre_t'    => $item->nbre_t,
                        'nbre_g'    => $item->nbre_g,
                        'nbre_f'    => $item->nbre_f,
                        'classee'   => $item->classee,
                        'no_classe' => $item->no_classe,
                        'admis'     => $item->admis,
                        'admis_g'   => $item->admis_g,
                        'admis_f'   => $item->admis_f,
                        'taux_a'    => $item->taux_a,
                        'taux_g'    => $item->taux_g,
                        'taux_f'    => $item->taux_f,
                    ];
                })->values()
            ];
        })
        ->values();
    }
    

    public function tauxReussite($cutting, $level) {
      return DB::table('registers as r')
      ->join('get_classes as gc', 'gc.id', '=', 'r.get_classe_id')
      ->join('levels as l', 'l.id', '=', 'gc.level_id')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->join('moyenne_trimestres as mt', 'mt.register_id', '=', 'r.id')
      ->where([
        'mt.cutting_school_year_id' => $cutting,
        'gc.school_id' => $this->schl,
        'l.id' => $level
      ])
      ->select('l.id', 'l.symbol',
        DB::raw('COUNT(*) as effectif'),
        DB::raw("SUM(CASE WHEN s.genre = 'm' THEN 1 ELSE 0 END) as garcons"),
        DB::raw(" SUM(CASE WHEN s.genre = 'f' THEN 1 ELSE 0 END) as filles"),
        DB::raw("SUM(CASE WHEN mt.moyenne >= 10 THEN 1 ELSE 0 END) as admis"),
        DB::raw("SUM(CASE WHEN mt.moyenne <> 'nc' THEN 1 ELSE 0 END) as classes"),
        DB::raw("SUM(CASE WHEN mt.moyenne = 'nc' THEN 1 ELSE 0 END) as non_classes"),
        DB::raw("SUM(CASE WHEN s.genre = 'm' AND mt.moyenne >= 10 THEN 1 ELSE 0 END) as admis_garcons"),
        DB::raw("SUM(CASE WHEN s.genre = 'f' AND mt.moyenne >= 10 THEN 1 ELSE 0 END) as admis_filles"),
        DB::raw("ROUND(
          SUM(CASE WHEN mt.moyenne >= 10 AND mt.moyenne <> 'nc' THEN 1 ELSE 0 END)
          * 100 / COUNT(*),
          2) as taux_reussite
        "),
        DB::raw("ROUND(
          SUM(CASE WHEN s.genre = 'm'
            AND mt.moyenne >= 10 AND mt.moyenne <> 'nc'
            THEN 1 ELSE 0 END) * 100
          / NULLIF(SUM(CASE WHEN s.genre = 'm' THEN 1 ELSE 0 END), 0
          ), 2) as taux_garcons
        "),
        DB::raw("ROUND(
          SUM(CASE WHEN s.genre = 'f'
            AND mt.moyenne >= 10 AND mt.moyenne <> 'nc'
            THEN 1 ELSE 0 END) * 100
          / NULLIF(SUM(CASE WHEN s.genre = 'f' THEN 1 ELSE 0 END), 0
          ), 2) as taux_filles
        ")
      )
      ->groupBy('l.id', 'l.symbol')
      ->first();
    }


    public function tauxResultatSerie($cutting, $level, $serie) {
      return DB::table('registers as r')
      ->join('get_classes as gc', 'gc.id', '=', 'r.get_classe_id')
      ->join('levels as l', 'l.id', '=', 'gc.level_id')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->join('moyenne_trimestres as mt', 'mt.register_id', '=', 'r.id')
      ->where([
        'mt.cutting_school_year_id' => $cutting,
        'gc.school_id' => $this->schl,
        'gc.serie_id' => $serie,
        'l.id' => $level
      ])
      ->select('l.id', 'l.symbol',
        DB::raw('COUNT(*) as effectif'),
        DB::raw("SUM(CASE WHEN s.genre = 'm' THEN 1 ELSE 0 END) as garcons"),
        DB::raw(" SUM(CASE WHEN s.genre = 'f' THEN 1 ELSE 0 END) as filles"),
        DB::raw("SUM(CASE WHEN mt.moyenne >= 10 THEN 1 ELSE 0 END) as admis"),
        DB::raw("SUM(CASE WHEN mt.moyenne <> 'nc' THEN 1 ELSE 0 END) as classes"),
        DB::raw("SUM(CASE WHEN mt.moyenne = 'nc' THEN 1 ELSE 0 END) as non_classes"),
        DB::raw("SUM(CASE WHEN s.genre = 'm' AND mt.moyenne >= 10 THEN 1 ELSE 0 END) as admis_garcons"),
        DB::raw("SUM(CASE WHEN s.genre = 'f' AND mt.moyenne >= 10 THEN 1 ELSE 0 END) as admis_filles"),
        DB::raw("ROUND(
          SUM(CASE WHEN mt.moyenne >= 10 AND mt.moyenne <> 'nc' THEN 1 ELSE 0 END)
          * 100 / COUNT(*),
          2) as taux_reussite
        "),
        DB::raw("ROUND(
          SUM(CASE WHEN s.genre = 'm'
            AND mt.moyenne >= 10 AND mt.moyenne <> 'nc'
            THEN 1 ELSE 0 END) * 100
          / NULLIF(SUM(CASE WHEN s.genre = 'm' THEN 1 ELSE 0 END), 0
          ), 2) as taux_garcons
        "),
        DB::raw("ROUND(
          SUM(CASE WHEN s.genre = 'f'
            AND mt.moyenne >= 10 AND mt.moyenne <> 'nc'
            THEN 1 ELSE 0 END) * 100
          / NULLIF(SUM(CASE WHEN s.genre = 'f' THEN 1 ELSE 0 END), 0
          ), 2) as taux_filles
        ")
      )
      ->groupBy('l.id', 'l.symbol')
      ->first();
    }


    public function getResultatScolaire($cutting) {
      return DB::table('registers as r')
        ->join('get_classes as gc', 'gc.id', '=', 'r.get_classe_id')
        ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
        ->join('students as s', 's.id', '=', 'ss.student_id')
        ->join('moyenne_trimestres as mt', 'mt.register_id', '=', 'r.id')
        ->where('mt.cutting_school_year_id', $cutting)
        ->where('gc.school_id', $this->schl)
        ->selectRaw("
          COUNT(*) as effectif,

          SUM(CASE WHEN s.genre = 'm' THEN 1 ELSE 0 END) as garcons,
          SUM(CASE WHEN s.genre = 'f' THEN 1 ELSE 0 END) as filles,

          SUM(CASE WHEN mt.moyenne >= 10 THEN 1 ELSE 0 END) as admis,
          SUM(CASE WHEN mt.moyenne < 10 THEN 1 ELSE 0 END) as redoublants,

          SUM(CASE WHEN mt.moyenne IS NOT NULL THEN 1 ELSE 0 END) as classes,
          SUM(CASE WHEN mt.moyenne IS NULL THEN 1 ELSE 0 END) as non_classes,

          SUM(CASE WHEN s.genre = 'm' AND mt.moyenne >= 10 THEN 1 ELSE 0 END) as admis_garcons,
          SUM(CASE WHEN s.genre = 'f' AND mt.moyenne >= 10 THEN 1 ELSE 0 END) as admis_filles,

          ROUND(
            SUM(CASE WHEN mt.moyenne >= 10 THEN 1 ELSE 0 END)
            * 100 /
            NULLIF(SUM(CASE WHEN mt.moyenne IS NOT NULL THEN 1 ELSE 0 END), 0),
          2) as taux_reussite,

          ROUND(
            SUM(CASE WHEN s.genre = 'm' AND mt.moyenne >= 10 THEN 1 ELSE 0 END)
            * 100 /
            NULLIF(SUM(CASE WHEN s.genre = 'm' AND mt.moyenne IS NOT NULL THEN 1 ELSE 0 END), 0),
          2) as taux_garcons,

          ROUND(
            SUM(CASE WHEN s.genre = 'f' AND mt.moyenne >= 10 THEN 1 ELSE 0 END)
            * 100 /
            NULLIF(SUM(CASE WHEN s.genre = 'f' AND mt.moyenne IS NOT NULL THEN 1 ELSE 0 END), 0),
          2) as taux_filles
        ")
        ->first();
    }


    public function statistikSave($level, $cutting, $effectif, $nbre_g, $nbre_f, $admis, $class, $nonClass, $admis_g, $admis_f, $result, $resutl_g, $resutl_f) {
      Statistik::updateOrCreate([
        'level_id' => $level,
        'school_id' => $this->schl,
        'cutting_school_year_id' => $cutting,
      ], 
      [
        'effectif_total' => $effectif,
        'effectif_garcon' => $nbre_g,
        'effectif_fille' => $nbre_f,
        'classified' => $class,
        'unranked' => $nonClass,
        'admis' => $admis,
        'admis_garcon' => $admis_g,
        'admis_fille' => $admis_f,
        'taux_total' => $result,
        'taux_garcon' => $resutl_g,
        'taux_fille' => $resutl_f
      ]);
    }


    public function SaveResultatGlobal($cutting, $nbre_t, $nbre_g, $nbre_f, $admis, $admis_g, $admis_f, $result, $taux_g, $taux_f, $classe, $no_classe) {
      ResultatScolaire::updateOrCreate([
        'school_id' => $this->schl,
        'cutting_school_year_id' => $cutting,
      ], 
      [
        'nbres_t' => $nbre_t,
        'nbres_g' => $nbre_g,
        'nbres_f' => $nbre_f,
        'admis' => $admis,
        'admis_g' => $admis_g,
        'admis_f' => $admis_f,
        'taux_a' => $result,
        'taux_g' => $taux_g,
        'taux_f' => $taux_f,
        'classee' => $classe,
        'non_classe' => $no_classe
      ]);
    }


    public function saveResultatSerie($level, $serie, $cutting, $nbre_t, $nbre_g, $nbre_f, $admis, $admis_g, $admis_f, $result, $taux_g, $taux_f, $classe, $no_classe) {
      StatistikSerie::updateOrCreate([
        'level_id' => $level,
        'serie_id' => $serie,
        'school_id' => $this->schl,
        'cutting_school_year_id' => $cutting,
      ], 
      [
        'nbre_t' => $nbre_t,
        'nbre_g' => $nbre_g,
        'nbre_f' => $nbre_f,
        'admis' => $admis,
        'admis_g' => $admis_g,
        'admis_f' => $admis_f,
        'taux_a' => $result,
        'taux_g' => $taux_g,
        'taux_f' => $taux_f,
        'classee' => $classe,
        'no_classe' => $no_classe
      ]);
    }


    private function resultatQuery($cutting) {
      $school = $this->school();
      return DB::table('levels as l')
      ->leftJoin('statistik_series as ss', function ($join) use ($cutting) {
        $join->on('ss.level_id', '=', 'l.id')
        ->join('series as s', 's.id' , '=', 'ss.serie_id')
        ->where([
          'ss.cutting_school_year_id' => $cutting,
          'ss.school_id' => $this->schl,
        ]);
      })
      ->where(function ($query) use ($school) {
        $query->Where('l.cycle2', $school['cycle2']);
      })
      ->select([
        'l.id', 'l.symbol', 's.libelle', 's.id as serie_id', 'ss.nbre_t', 'ss.nbre_g',
        'ss.nbre_f', 'ss.classee', 'ss.no_classe', 'ss.admis', 'ss.admis_g',
        'ss.admis_f', 'ss.taux_a', 'ss.taux_g', 'ss.taux_f'
      ])
      ->orderBy('l.id')->get();
    }


    private function school() {
      return School::find($this->schl) ?? null;
    }
  }