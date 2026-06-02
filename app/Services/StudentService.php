<?php
  namespace App\Services;

  use App\Models\Tuteur;
  use App\Models\Student;
  use App\Models\SchoolYear;
  use App\Models\Notionality;
  use App\Models\SchoolStudent;  
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;
  use Yajra\DataTables\Facades\DataTables;
  class StudentService
  {
    private $schl;
    public function __construct() {
      $this->schl = Auth::user()->school_id ?? 1;
    }

    public function getYajra() {
      $query = DB::table('school_students as ss')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->select(['ss.id', 'ss.residence', 's.matricul', 's.first', 's.last', 's.genre'])
      ->where('ss.status', '1')->orderBy('s.first')->orderBy('s.last')->get();
      $compte = 0;
      return DataTables::of($query)
      ->addColumn('compte', function() use (&$compte) {
        return ($compte < 9 ? '0'.++$compte : ++$compte);
      })
      ->addColumn('matricul', function ($row) {
        return ($row->matricul);
      })
      ->addColumn('first', function ($row) {
        return (strtoupper($row->first));
      })
      ->addColumn('last', function ($row) {
        return (ucwords($row->last));
      })
      ->addColumn('genre', function ($row) {
        return (ucwords($row->genre == 'F' ? 'Feminin':'Masculin'));
      })
      ->addColumn('residence', function ($row) {
        return (ucwords($row->residence));
      })
      ->addColumn('action', function ($row) {
        $edit = route('student.edit', $row->id);
        return ('<span class="card-block remove-label m-0 pb-0 text-center">
          <a href="#" class="btn btn-sm btn-warning text-white py-1 me-2">Detail</a>
          <a href="'.$edit.'" class="btn btn-sm btn-primary me-2">Edit</a>
        </span>');
      })
      ->rawColumns(['compte', 'matricul', 'first', 'last', 'genre', 'residence', 'action'])
      ->make(true);
    }

    public function getDtYears($str) {
      $query = DB::table('school_students as ss')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->select(['ss.id', 'ss.residence', 's.matricul', 's.first', 's.last', 's.genre'])
      ->where('ss.status', '1')->where('ss.school_year_id', $str)
      ->orderBy('s.first')->orderBy('s.last')->get();
      $compte = 0;
      return DataTables::of($query)
      ->addColumn('compte', function() use (&$compte) {
        return ($compte < 9 ? '0'.++$compte : ++$compte);
      })
      ->addColumn('matricul', function ($row) {
        return ($row->matricul);
      })
      ->addColumn('first', function ($row) {
        return (strtoupper($row->first));
      })
      ->addColumn('last', function ($row) {
        return (ucwords($row->last));
      })
      ->addColumn('genre', function ($row) {
        return (ucwords($row->genre == 'F' ? 'Feminin':'Masculin'));
      })
      ->addColumn('residence', function ($row) {
        return (ucwords($row->residence));
      })
      ->addColumn('action', function ($row) {
        $edit = route('student.edit', $row->id);
        return ('<span class="card-block remove-label m-0 pb-0 text-center">
          <a href="#" class="btn btn-sm btn-warning text-white py-1 me-2">Detail</a>
          <a href="'.$edit.'" class="btn btn-sm btn-primary me-2">Edit</a>
        </span>');
      })
      ->rawColumns(['compte', 'matricul', 'first', 'last', 'genre', 'residence', 'action'])
      ->make(true);
    }

    public function getStdt($str) {
      $dt = SchoolStudent::find($str);
      return $dt ?? null;
    }

    public function getYears() {
      $dts = SchoolYear::orderBy('created')->get();
      return $dts ?? [];
    }

    public function getYear($id) {
      $dt = SchoolYear::find($id);
      return $dt ?? null;
    }

    public function getStore($data) {
      $nation = $this->nation($data['nation']);
      if($nation) {
        $std = $this->student($data['matricul'], $data['first'], $data['last'], $data['genre'], $data['date'], $data['lieu'], $nation);
        $parent = $this->parent($data['nom'], $data['prenom'], $data['civilit'], $data['telephon'], $data['email']);
        if($std && $parent) {
          SchoolStudent::firstOrCreate([
            'student_id' => $std,
            'school_id' => $this->schl,
          ], [
            'tuteur_id' => $parent,
            'type' => strtolower($data['type']),
            'residence' => strtolower($data['residence']),
            'school_year_id' => $this->year()
          ]);
        }
      }
    }

    public function update($str, $data) {
      $dts = $this->updateSchl($str, $data['type'], $data['residence'], $data['status']);
      if($dts){
        $this->updateTtr($dts['tuteur_id'], $data['nom'], $data['prenom'], $data['civilit'], $data['telephon'], $data['email']);
        $nation = $this->nation($data['nation']);
        if($nation) {
          $this->updateStd($dts['student_id'], $data['matricul'], $data['first'], $data['last'], $data['genre'], $data['date'], $data['lieu'], $nation);
        }
      }
    }

    public function export() {
      return $this->schl.$this->year();
    }

    private function nation($libelle) {
      $libelle = strtolower($libelle);
      $val = Notionality::where('libelle', 'like', "{$libelle}%")->first();
      if ($val) {
        return $val->id;
      }
      $val = Notionality::create([
        'libelle' => $libelle
      ]);
      return $val->id;
    }

    private function parent($first, $last, $sexe, $phon, $email = null) {
      return Tuteur::firstOrCreate([
        'telephon' => $phon
      ], [
        'first' => strtolower($first),
        'last' => strtolower($last),
        'civilit' => $sexe,
        'email' => $email
      ])->id;
    }

    private function student($matricul, $first, $last, $sexe, $date, $lieu, $nation) {
      return Student::firstOrCreate([
        'matricul' => strtoupper($matricul)
      ], [
        'first' => strtolower($first),
        'last' => strtolower($last),
        'genre' => $sexe,
        'date' => $date,
        'lieu' => strtolower($lieu),
        'notionalitie_id' => $nation
      ])->id;
    }

    private function updateSchl($id, $type, $lieu, $status = null) {
      $dts = SchoolStudent::find($id);
      if (!$dts) {
        return null;
      }

      $dts->update([
        'type' => strtolower(trim($type)),
        'residence' => strtolower(trim($lieu)),
        'status' => $status ? '1' : '0',
      ]);
      return $dts;
    }

    private function updateTtr($id, $first, $last, $sexe, $phon, $email = null) {
      return Tuteur::where('id', $id)->update([
        'first' => strtolower($first),
        'last' => strtolower($last),
        'telephon' => $phon,
        'civilit' => $sexe,
        'email' => $email
      ]);
    }


    private function updateStd($id, $matricul, $first, $last, $sexe, $date, $lieu, $nation) {
      return Student::where('id', $id)->update([
        'matricul' => strtoupper($matricul),
        'first' => strtolower($first),
        'last' => strtolower($last),
        'genre' => $sexe,
        'date' => $date,
        'lieu' => $lieu,
        'notionalitie_id' => $nation
      ]);
    }
    
    private function year() {
      $year = SchoolYear::where('status', '1')->first();
      return $year ? $year->id:null;
    }
  }