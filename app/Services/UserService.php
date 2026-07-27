<?php
  namespace App\Services;

  use App\Models\User;
  use App\Models\Role;
  use App\Models\Level;
  use App\Models\School;
  use App\Models\SchoolYear;
  use Yajra\DataTables\Facades\DataTables;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Support\Str;

  class UserService
  {
    private const ROLE  = 8;
    private const MOT_PASSE = '000000';
    private const DIRECTEUR = 4;
    private const FONDATEUR = 3;
    private $schl;

    public function __construct() {
      $user = getUserGlobal();
      $this->schl = $user ? $user->school_id:null;
    }

    public function datatable($status) {
      $query = user::where('school_id', $this->schl)
      ->where('status', $status)
      ->where('role_id', '!=', self::ROLE)
      ->orderBy('first_name')
      ->orderBy('last_name')->get();
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
      ->addColumn('profil', function ($row) {
        return (ucwords($row->role->libelle));
      })
      ->addColumn('action', function ($row) {
        $url = route('teacher.edit', $row->id);
        return (
          '<a href="'.$url.'" class="btn btn-sm btn-outline-light py-0"><i class="fas fa-ellipsis-h"></i></a>'
        );
      })
      ->rawColumns(['compte', 'first', 'last', 'sexe', 'email', 'phon', 'profil', 'action'])
      ->make(true);
    }

    public function getRole() {
      return Role::where('status', '1')
      ->where('id', '!=', 8)->get();
    }


    public function getLevels() {
      $school = $this->school();
      $levels = Level::query()
      ->where(function ($query) use ($school) {
        $query->where('cycle1', $school['cycle1'])
        ->orWhere('cycle2', $school['cycle2']);
      })
      ->orderBy('id')
      ->get();
      return $levels ?? [];
    }


    public function getStoreUser($data) {
      if(in_array($data['role'], [self::DIRECTEUR, self::FONDATEUR])) {
        User::where('role_id', $data['role'])->where('school_id', $this->schl)
        ->update(['status' => '0']);
      }

      $this->userStore(
        $data['role'], $data['first'], $data['last'], $data['civility'], $data['email'], $data['phon']
      );
    }


    private function userStore($role, $first, $last, $civilit, $email, $phon, $user = null) {
      User::create([
        'first_name' => strtolower($first),
        'last_name' => strtolower($last),
        'civility' => $civilit,
        'email' => $email,
        'telephon' => $phon,
        'role_id' => $role,
        'school_id' => $this->schl,
        'personnel_id' => $user,
        'school_year_id' => $this->year(),
        'email_verified_at' => now(),
        'password' =>  Hash::make(self::MOT_PASSE),
        'remember_token' => Str::random(10)
      ]);
    }

    private function year() {
      $dts = SchoolYear::where('status', '1')->first();
      return $dts ? $dts['id']:null;
    }

    private function school() {
      return School::find($this->schl) ?? null;
    }
  }