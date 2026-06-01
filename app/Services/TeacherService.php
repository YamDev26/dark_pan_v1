<?php
  namespace App\Services;

  use App\Models\User;
  use App\Models\Matter;
  use App\Models\School;
  use App\Models\Teacher;
  use App\Models\SchoolYear;
  use Yajra\DataTables\Facades\DataTables;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Support\Str;
  class TeacherService
  {
    private const ROLE  = 8;
    private const MOT_PASSE = '000000';
    private $schl;

    public function __construct() {
      $this->schl = Auth::user()->school_id ?? 1;
    }

    public function getYajra() {
      $query = user::where('school_id', $this->schl)
      ->where(['status' => '1', 'role_id' => self::ROLE])
      ->orderBy('first_name')
      ->orderBy('last_name')
      ->get();
      $compte = 0;
      return DataTables::of($query)
      ->addColumn('compte', function() use (&$compte) {
        return ($compte < 9 ? '0'.++$compte : ++$compte);
      })
      ->addColumn('first', function ($row) {
        return (strtoupper($row->first_name));
      })
      ->addColumn('last', function ($row) {
        return (ucwords($row->last_name));
      })
      ->addColumn('sexe', function ($row) {
        return (ucwords($row->civility == 'mr' ? 'Homme':'Femme'));
      })
      ->addColumn('email', function ($row) {
        return ($row->email);
      })
      ->addColumn('phon', function ($row) {
        return ($row->telephon);
      })
      ->addColumn('action', function ($row) {
        return (
          '<a href="#" class="btn btn-sm btn-info text-white py-1">Detail</a>'
        );
      })
      ->rawColumns(['compte', 'first', 'last', 'sexe', 'email', 'phon', 'action'])
      ->make(true);
    }

    public function getMatters() {
      $school = $this->school();
      $excluded = [];
      if (!$school['informatik']) {
        $excluded[] = 12; // id Informatique
      }
      if (!$school['autres']) {
        $excluded[] = 10; // id Musique/Arts Plastique
      }
      return Matter::query()
      ->where('id', '<', 13)
      ->when($excluded, fn ($q) => $q->whereNotIn('id', $excluded))
      ->orderByRaw('bilan_matter_id, position')
      ->get();
    }

    public function getStore($data) {
      $teacher = $this->teacher($data);
      if($teacher) {
        User::create([
          'first_name' => strtolower($data['first']),
          'last_name' => strtolower($data['last']),
          'civility' => $data['civility'],
          'email' => $data['email'],
          'telephon' => $data['phon'],
          'role_id' => self::ROLE,
          'school_id' => $this->schl,
          'teacher_id' => $teacher,
          'school_year_id' => $this->year(),
          'email_verified_at' => now(),
          'password' =>  Hash::make(self::MOT_PASSE),
          'remember_token' => Str::random(10)
        ]);
      }
    }
    

    private function teacher($data) {
      $data = Teacher::create([
        'date_naiss' => $data['date'],
        'lieu_naiss' => strtolower($data['lieu']),
        'piece' => $data['piece'],
        'num_piece' => $data['numero'],
        'etude' => $data['etude'],
        'diplome' => strtolower($data['diplom']),
        'type' => $data['enseignant'],
        'autorisate' => $data['autorisate'] == 'oui' ? true:false,
        'num_autorisate' => $data['num_auto'],
        'date_autorisate' => $data['date_acquise'],
        'matter_id' => $data['matter'],
        'experiens' => $data['experiens'],
      ]);
      return $data ? $data['id']:null;
    }


    private function year() {
      $dts = SchoolYear::where('status', '1')->first();
      return $dts ? $dts['id']:null;
    }

    private function school() {
      return School::find($this->schl) ?? null;
    }
  }