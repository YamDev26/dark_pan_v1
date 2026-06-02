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

    public function getYajra($status) {
      $query = user::where('school_id', $this->schl)
      ->where(['status' => $status, 'role_id' => self::ROLE])
      ->orderBy('first_name')->orderBy('last_name')->get();
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
      ->addColumn('matter', function ($row) {
        return (ucwords($row->teacher->matter->libelle));
      })
      ->addColumn('action', function ($row) {
        $url = route('teacher.edit', $row->id);
        return (
          '<a href="'.$url.'" class="btn btn-sm btn-info text-white py-1">Detail</a>'
        );
      })
      ->rawColumns(['compte', 'first', 'last', 'sexe', 'email', 'phon', 'matter', 'action'])
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

    public function getTeacher($id) {
      $user = User::find($id);
      return $user ?? null;
    }

    public function getStore($data) {

      $id = $this->teacher(
        $data['date'], $data['lieu'], $data['piece'], $data['numero'], $data['etude'],
        $data['diplom'], $data['enseignant'], $data['autorisate'], $data['num_auto'],
        $data['date_acquise'], $data['matter'], $data['experiens']
      );
      if($id) {
        $this->userStore($data['first'], $data['last'], $data['civility'], $data['email'], $data['phon'], $id);
      }
    }


    public function getUpdate($str, $data) {
      $user = User::find($str);

      if($user) {
        $user->update([
          'first_name' => strtolower($data['first']),
          'last_name' => strtolower($data['last']),
          'civility' => $data['civility'],
          'email' => $data['email'],
          'telephon' => $data['phon'],
          'status' => $data['status'] ? '1':'0'
        ]);
        $this->updateTeacher($user['teacher_id'], $data);
      }

    }


    public function verifyUser($email, $phon) {
      return User::where('email', $email)->orWhere('telephon', $phon)->count();
    }
    

    public function getImport($data) {
      $id = $this->teacher(
        $data['date_naissance'], $data['lieu_naissance'], $data['piece_identite'], $data['numero_piece'], $data['niveau_etude'],
        $data['denier_diplome'], $data['type_contrat'], $data['autorisation'], $data['numero_autorisation'],
        $data['date_acquisition'], $data['matiere_enseignee'], $data['experience']
      );

      if($id) {
        $this->userStore($data['nom'], $data['prenoms'], $data['civilite'], $data['adresse_email'], $data['telephone'], $id);
      }
    }


    public function export() {
      return $this->schl.$this->year();
    }


    public function searchMatter($search) {
      $matter = Matter::where('libelle', 'like', "{$search}%")
      ->orWhere('symbol', 'like', "{$search}%")->first();
      return $matter ? $matter->id:8;
    }


    private function teacher($date, $lieu, $piece, $nmro, $etude, $diplme, $type, $auto, $nmr_auto, $dt_auto, $matter, $experiens) {
      $data = Teacher::create([
        'date_naiss' => $date,
        'lieu_naiss' => strtolower($lieu),
        'piece' => $piece,
        'num_piece' => $nmro,
        'etude' => $etude,
        'diplome' => strtolower($diplme),
        'type' => strtolower($type),
        'autorisate' => $auto == 'oui' ? true:false,
        'num_autorisate' => $nmr_auto,
        'date_autorisate' => $dt_auto,
        'matter_id' => $matter,
        'experiens' => $experiens
      ]);
      return $data ? $data['id']:null;
    }
    

    public function destroy($str) {
      $user = User::find($str);
      if($user) {
        Teacher::where('id', $user['teacher_id'])->delete();
      }
    }


    private function userStore($first, $last, $civilit, $email, $phon, $teacher) {
      User::create([
        'first_name' => strtolower($first),
        'last_name' => strtolower($last),
        'civility' => $civilit,
        'email' => $email,
        'telephon' => $phon,
        'role_id' => self::ROLE,
        'school_id' => $this->schl,
        'teacher_id' => $teacher,
        'school_year_id' => $this->year(),
        'email_verified_at' => now(),
        'password' =>  Hash::make(self::MOT_PASSE),
        'remember_token' => Str::random(10)
      ]);
    }

    private function updateTeacher($str, $data) {
      Teacher::where('id', $str)->update([
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
    }


    private function year() {
      $dts = SchoolYear::where('status', '1')->first();
      return $dts ? $dts['id']:null;
    }

    private function school() {
      return School::find($this->schl) ?? null;
    }
  }