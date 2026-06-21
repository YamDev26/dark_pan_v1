<?php
  namespace App\Services;


  use App\Models\School;
  use App\Models\SchoolYear;
  use App\Models\CuttingSchoolYear;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;
  
  class DashboardService
  {
    private const ACTIF  = '1';
    private const TERMINE  = '3';
    private $schl;

    public function __construct() {
      $this->schl = Auth::user()->school_id ?? 1;
    }
    
    
    public function updateCuttingDate() {
    
      $year = $this->actifYear();
      if (! $year) {
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