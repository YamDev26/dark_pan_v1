<?php
  namespace App\Services;

  use App\Models\School;
  use App\Models\Statistik;
  use App\Models\GetClasse;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;
  
  class StatistikService
  {
    private $schl;

    public function __construct() {
      $this->schl = Auth::user()->school_id ?? 1;
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


    public function getclasse($str) {
      return GetClasse::find($str);
    }


    public function statistikSave($level, $cutting, $effectif, $nbre_g, $nbre_f, $admis, $class, $nonClass, $admis_g, $admis_f, $result, $resutl_g, $resutl_f) {
      Statistik::firstOrCreate([
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
        'taux_fille' => $resutl_f,
      ]);
    }


    private function school() {
      return School::find($this->schl) ?? null;
    }
  }