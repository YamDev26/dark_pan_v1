<?php
  namespace App\Services;

  use App\Models\School;
  use App\Models\Statistik;
  use App\Models\GetClasse;
  use App\Models\StatistikSerie;
  use App\Models\ResultatScolaire;
  use App\Models\CuttingSchoolYear;
  use App\Models\CuttingCloseSchool;
  use Illuminate\Support\Facades\DB;
  
  class StatistikService
  {
    private const DIRECTEUR = 4;
    private const FONDATEUR = 3;
    private const ADMIN = 2;
    private const A_LEVEL = 5;


    public function getclasse($str) {
      return GetClasse::find($str);
    }
    

    public function getCutting($str) {
      return CuttingSchoolYear::find($str);
    }

    public function getCloseCutting($cutting) {
      $user = $this->user();
      $verify = CuttingCloseSchool::where('school_id', $user['school'])
      ->where('cutting_school_year_id', $cutting)->first();
      
      if($verify) {
        return false;
      }
      return in_array($user['role'], [self::ADMIN, self::FONDATEUR, self::DIRECTEUR]) 
      ? true:false;
    }

    public function getStatistikTotal($cutting) {

      $school = $this->school();
      return DB::table('levels as l')
      ->leftJoin('statistiks as s', function ($join) use ($cutting, $school) {
        $join->on('s.level_id', '=', 'l.id')
        ->where([
          'cutting_school_year_id' => $cutting,
          'school_id' => $school->id,
        ]);
      })
      ->where(function ($query) use ($school) {
        $query->where('l.cycle1', $school['cycle1'])
        ->orWhere('l.cycle2', $school['cycle2']);
      })
      ->select([
        'l.symbol', 's.nbres_t', 's.nbres_g', 's.nbres_f',
        's.classee', 's.non_classe', 's.admis', 's.admis_g',
        's.admis_f', 's.taux_a', 's.taux_g', 's.taux_f'
      ])
      ->orderBy('l.id')->get();
    }


    public function getResultatCycle1($cutting) {
      $school = $this->school();
      return DB::table('levels as l')
      ->leftJoin('statistiks as s', function ($join) use ($cutting, $school) {
        $join->on('s.level_id', '=', 'l.id')
        ->where([
          'cutting_school_year_id' => $cutting,
          'school_id' => $school->id,
        ]);
      })
      ->where(function ($query) use ($school) {
        $query->where('l.cycle1', $school['cycle1']);
      })
      ->select([
        'l.symbol', 's.nbres_t', 's.nbres_g', 's.nbres_f',
        's.classee', 's.non_classe', 's.admis', 's.admis_g',
        's.admis_f', 's.taux_a', 's.taux_g', 's.taux_f'
      ])
      ->orderBy('l.id')->get();
    }


    public function getResultat($cutting, $type) {
      $user = $this->user();
      return ResultatScolaire::where('school_id', $user['school'])
      ->where('cutting_school_year_id', $cutting)
      ->where('type', $type)->first();
    }


    public function getResultatCycle2($cutting) {
      return $this->resultatQuery($cutting)
      ->groupBy('symbol')
      ->map(function ($items) {
        $first = $items->first();
        return [
          'niveau' => $first->symbol,
          'series' => $items->map(function ($item) {
            return [
              'serie'     => $item->libelle,
              'nbres_t'    => $item->nbres_t,
              'nbres_g'    => $item->nbres_g,
              'nbres_f'    => $item->nbres_f,
              'classee'   => $item->classee,
              'non_classe' => $item->non_classe,
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
    

    public function tauxReussite($cutting, $level, $school) {
      return DB::table('registers as r')
      ->join('get_classes as gc', 'gc.id', '=', 'r.get_classe_id')
      ->join('levels as l', 'l.id', '=', 'gc.level_id')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->join('moyenne_trimestres as mt', 'mt.register_id', '=', 'r.id')
      ->where([
        'mt.cutting_school_year_id' => $cutting,
        'gc.school_id' => $school,
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


    public function tauxResultatSerie($cutting, $level, $serie, $school) {
      return DB::table('registers as r')
      ->join('get_classes as gc', 'gc.id', '=', 'r.get_classe_id')
      ->join('levels as l', 'l.id', '=', 'gc.level_id')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->join('moyenne_trimestres as mt', 'mt.register_id', '=', 'r.id')
      ->where([
        'mt.cutting_school_year_id' => $cutting,
        'gc.school_id' => $school,
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


    public function getResultatCycle($cutting, $signe, $school) {
      return DB::table('registers as r')
      ->join('get_classes as gc', 'gc.id', '=', 'r.get_classe_id')
      ->join('levels as l', 'l.id', '=', 'gc.level_id')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->join('moyenne_trimestres as mt', 'mt.register_id', '=', 'r.id')
      ->where('mt.cutting_school_year_id', $cutting)
      ->where('gc.level_id', $signe ,self::A_LEVEL)
      ->where('gc.school_id', $school)
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


    public function getResultatScolaire($cutting, $school) {
      return DB::table('registers as r')
      ->join('get_classes as gc', 'gc.id', '=', 'r.get_classe_id')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->join('moyenne_trimestres as mt', 'mt.register_id', '=', 'r.id')
      ->where('mt.cutting_school_year_id', $cutting)
      ->where('gc.school_id', $school)
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


    public function statistikSave($level, $cutting, $school, $table) {
      Statistik::updateOrCreate([
        'level_id' => $level,
        'school_id' => $school,
        'cutting_school_year_id' => $cutting,
      ], 
      $this->colomnTable($table));
    }


    public function SaveResultatGlobal($cutting, $type, $school, $table) {
      ResultatScolaire::updateOrCreate([
        'type'  => $type,
        'school_id' => $school,
        'cutting_school_year_id' => $cutting,
      ], 
      $this->colomnTable($table));
    }


    public function saveResultatSerie($level, $serie, $cutting, $school, $table) {
      StatistikSerie::updateOrCreate([
        'level_id' => $level,
        'serie_id' => $serie,
        'school_id' => $school,
        'cutting_school_year_id' => $cutting,
      ],
      $this->colomnTable($table));
    }


    public function storeCuttingClose($cutting) {

      $user = $this->user();

      if(!in_array($user['role'], [self::ADMIN, self::FONDATEUR, self::DIRECTEUR])) {
        return ([
          'str' => 'danger',
          'msg' =>'Vous n\êtes pas autorisé pour cette action !'
        ]);
      }

      CuttingCloseSchool::updateOrCreate([
        'school_id' => $user['school'],
        'cutting_school_year_id' => $cutting,
      ]);
      return ([
        'str' => 'success',
        'msg' => 'Cette action s\'est déroulée avec succès.'
      ]);
    }


    private function resultatQuery($cutting) {
      $school = $this->school();
      return DB::table('levels as l')
      ->leftJoin('statistik_series as ss', function ($join) use ($cutting, $school) {
        $join->on('ss.level_id', '=', 'l.id')
        ->join('series as s', 's.id' , '=', 'ss.serie_id')
        ->where([
          'ss.cutting_school_year_id' => $cutting,
          'ss.school_id' => $school->id
        ]);
      })
      ->where(function ($query) use ($school) {
        $query->Where('l.cycle2', $school['cycle2']);
      })
      ->select([
        'l.id', 'l.symbol', 's.libelle', 's.id as serie_id', 'ss.nbres_t', 'ss.nbres_g',
        'ss.nbres_f', 'ss.classee', 'ss.non_classe', 'ss.admis', 'ss.admis_g',
        'ss.admis_f', 'ss.taux_a', 'ss.taux_g', 'ss.taux_f'
      ])
      ->orderBy('l.id')->get();
    }


    private function colomnTable($table) {
      return [
        'nbres_t'    => $table[0],
        'nbres_g'    => $table[1],
        'nbres_f'    => $table[2],
        'admis'      => $table[3],
        'admis_g'    => $table[4],
        'admis_f'    => $table[5],
        'taux_a'     => $table[6],
        'taux_g'     => $table[7],
        'taux_f'     => $table[8],
        'classee'    => $table[9],
        'non_classe' => $table[10]
      ];
    }

    private function user() {
      $user = getUserGlobal();
      return [
        'school' => $user->school_id,
        'role' => $user->role->libelle,
      ];
    }

    private function school() {
      $user = getUserGlobal();
      return School::find($user->school_id) ?? null;
    }
  }