<?php
  namespace App\Services;

  use App\Models\School;
  use App\Models\SlotTime;
  use App\Models\DaysWeek;
  use App\Models\SchoolYear;
  use App\Models\CuttingSchoolYear;
  use Illuminate\Support\Facades\DB;
  
  class DashboardService
  {
    private const ACTIF  = '1';
    private const TERMINE  = '3';
    private $schl, $user;

    public function __construct() {
      $user = getUserGlobal();
      $this->user = $user ? $user->id:null;
      $this->schl = $user ? $user->school_id:null;
    }
    
    
    public function updateCuttingDate() {
    
      $year = $this->actifYear();
      if (!$year) {
        return;
      }

      if($this->verifyUpdate($year->id)) {
        return; 
      }

      CuttingSchoolYear::where('school_year_id', $year->id)
      ->where('status', '<', self::TERMINE)
      ->update([
        'status' => DB::raw("
          CASE
            WHEN CURDATE() < debut THEN 1
            WHEN CURDATE() BETWEEN debut AND fin THEN 2
            ELSE 3
          END
        "),
        'updated' => DB::raw('CURDATE()'),
      ]);

    }


    public function getDayWeek() {
      return DaysWeek::orderBy('order')->get();
    }


    public function getTime() {
      $times = SlotTime::where('school_id', $this->schl)
      ->orderBy('order')
      ->get()
      ->groupBy('period');
      return [$times->get(1, collect()), $times->get(2, collect())];
    }


    public function getTableTime() {
      $schoolYearId = $this->actifYear()['id'];
      return DB::table('classe_teachers as ct')
      ->join('get_classes as gc', 'gc.id', '=', 'ct.get_classe_id')
      ->join('level_matters as lm', 'lm.id', '=', 'ct.level_matter_id')
      ->join('matters as m', 'm.id', '=', 'lm.matter_id')
      ->join('table_times as tt', function ($join) {
        $join->on('tt.level_matter_id', '=', 'ct.level_matter_id')
        ->on('tt.get_classe_id', '=', 'ct.get_classe_id');
      })
      ->where([
        'ct.user_id' => $this->user,
        'gc.school_id' => $this->schl,
        'gc.school_year_id' => $schoolYearId
      ])
      ->select([
        'gc.libelle as classe',
        'tt.days_week_id as days',
        'tt.slot_time_id as time',
        'm.symbol as matter',
        'tt.period',
      ])
      ->get();
    }


    public function nbreClasseTeacher() {
      $schoolYearId = $this->actifYear()['id'];
      return DB::table('classe_teachers as ct')
      ->join('get_classes as gc', 'gc.id', '=', 'ct.get_classe_id')
      ->where([
        'ct.user_id' => $this->user,
        'gc.school_id' => $this->schl,
        'gc.school_year_id' => $schoolYearId
      ])
      ->distinct('gc.id')
      ->count();
    }


    private function verifyUpdate($year) {
      $date = now()->toDateString();
      return CuttingSchoolYear::where('school_year_id', $year)
      ->where('updated', $date)->count();
    }

    
    private function actifYear() {
      return SchoolYear::where('status', self::ACTIF)->first();
    }


    private function school() {
      return School::find($this->schl) ?? null;
    }
  }