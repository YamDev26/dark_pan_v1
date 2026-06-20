<?php
  namespace App\Services;

  use App\Models\User;
  use App\Models\School;
  use App\MOdels\Cutting;
  use App\Models\DrenSchool;
  use App\Models\SchoolYear;
  use App\Models\Notionality;
  use App\Models\CuttingSchoolYear;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Support\Str;
  use Carbon\Carbon;
  class SuperAdminService
  {
    private const A_VENIR  = '1';
    private const EN_COURS = '2';
    private const TERMINE  = '3';
    private const MOT_PASSE = '000000';

    public function dataYear() {
      $year = $this->actifYear();
      $verify = CuttingSchoolYear::where('school_year_id', $year->id)->count();
      return [$year, $verify];
    }

    public function createYear($data) {
      if($data['checked']){
        SchoolYear::where('status', '1')->update(['status' => '0']);
      }
      SchoolYear::create([
        'libelle' => $data['year'],
        'decoupe' => $data['radio'],
        'created' => $data['current'],
        'status' => $data['checked'] ? '1':'0'
      ]);
    }


    public function editYear($data, $str) {
      $dts = $this->yearShool($str); 
      if($dts) {
        if($data['checked']){
          SchoolYear::where('status', '1')->update(['status' => '0']);
        }
        $dts->update([
          'libelle' => $data['year'],
          'decoupe' => $data['radio'],
          'status' => $data['checked'] ? '1':'0'
        ]);
      }
    }


    public function getYear() {
      $data = SchoolYear::orderBy('created')->get();
      return $data;
    }


    public function yearShool($id) {
      $data = SchoolYear::find($id);
      return $data;
    }


    public function cutting() {
      $year = $this->actifYear();
      return Cutting::where('type', $year->decoupe)->get();
    }


    public function getCutting($year) {
      $dts = CuttingSchoolYear::where('school_year_id', $year)->get();
      return $dts;
    }


    public function createCtg($dts) {
      $current = Carbon::now(); $i = 0;
      while( $i < sizeof($dts['str'])) {
        $status = $this->compareToDate($current, $dts['dbt'][$i], $dts['fin'][$i]);
        $status == 2 ? CuttingSchoolYear::where('status', '1')->update(['status' => '3']):null;
        $this->SaveCutting($dts['year'], $dts['str'][$i], $dts['dbt'][$i], $dts['fin'][$i], $status);
        $i++;
      }
    }


    public function updateCutting($dts) {
      CuttingSchoolYear::where('status', '1')->update(['status' => '0']);
      $current = Carbon::now(); $i = 0;
      while( $i < sizeof($dts['str'])) {
        $status = $this->compareToDate($current, $dts['dbt'][$i], $dts['fin'][$i]);
        CuttingSchoolYear::where('id', $dts['str'][$i])->update([
          'status' => $status,
          'fin' => $dts['fin'][$i],
          'debut' => $dts['dbt'][$i]
        ]);
        $i++;
      }
    }


    public function country() {
      $dts = Notionality::orderBy('libelle')->get();
      return $dts;
    }


    public function getCountry($id) {
      return Notionality::find($id);
    }


    public function editCountry($data) {
      Notionality::where('id', $data->id)->update([
        'libelle' => strtolower($data->libelle),
        'status' => $data->str ? '1':'0'
      ]);
    }


    public function index() {
      $dts = School::select('id', 'name', 'autorisation', 'code', 'status')->orderBy('name')->get();
      return $dts;
    }

    public function update($id) {
      $dts = School::select('id', 'name', 'autorisation', 'code', 'status')->where('id', $id)->first();
      return $dts;
    }

    public function stored($dts) {
      $id = $this->school($dts['code'], $dts['num'], $dts['name']);
      if($id) {
        User::create([
          'first_name' => strtolower($dts['first']),
          'last_name' => strtolower($dts['last']),
          'email' => strtolower($dts['email']),
          'civility' => strtolower($dts['genre']),
          'telephon' => $dts['phon'],
          'school_id' => $id,
          'role_id' => self::EN_COURS,
          'email_verified_at' => now(),
          'password' => Hash::make(self::MOT_PASSE),
          'remember_token' => Str::random(10)
        ]);
      }
    }

    public function updated($data, $str) {
      School::where('id', $str)->update([
        'code' => $data['code'],
        'autorisation' => $data['num'],
        'name' => strtolower($data['name']),
        'status' => $data['status'] ? '1':'0'
      ]);
    }


    public function getDren() {
      $dts = DrenSchool::orderBy('id', 'ASC')->paginate(10);
      return $dts ?? [];
    }
    
    // Function Private -------
    private function actifYear() {
      return SchoolYear::where('status', '1')->first();
    }

    private function compareToDate($actuel, $debut, $fin): string {
      $actuel = Carbon::parse($actuel);
      $debut  = Carbon::parse($debut);
      $fin    = Carbon::parse($fin);
      return match(true) {
        $actuel->lt($debut) => self::A_VENIR,
        $actuel->lte($fin)  => self::EN_COURS,
        default             => self::TERMINE,
      };
    }

    private function SaveCutting($year, $cutting, $debt, $fin, $status) {
      CuttingSchoolYear::create([
        'fin' => $fin,
        'debut' => $debt,
        'status' => $status,
        'cutting_id' => $cutting,
        'school_year_id' => $year,
      ]);
    }

    private function school($code, $num, $name) {
      $dt = School::create([
        'code' => $code,
        'autorisation' => $num,
        'name' => strtolower($name)
      ]);
      return $dt ? $dt->id:null;
    }

  }