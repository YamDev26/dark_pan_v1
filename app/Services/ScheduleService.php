<?php
  namespace App\Services;

  use App\Models\School;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;

  class ScheduleService
  {
    private $schl;
    public function __construct() {
      $this->schl = Auth::user()->school_id ?? 1;
    }
    
    
    

    private function school() {
      return School::find($this->schl) ?? null;
    }
  }