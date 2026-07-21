<?php
  namespace App\Services;

  use App\Models\Evaluat;
  use App\Models\Evaluated;
  use App\Models\SubMatter;
  use App\Models\GetClasse;
  use App\Models\SchoolYear;
  use App\Models\LevelMatter;
  use App\Models\EvaluatedType;
  use App\Models\CuttingSchoolYear;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;
  use Yajra\DataTables\Facades\DataTables;
  
  class EnseigmntEvaluatedService
  {
    private $user; private const NOTE  = 20;

    public function __construct() {
      $this->user = Auth::user();
    }
    
    
    public function classe($str) {
      return GetClasse::find($str);
    }

    public function matter($str) {
      return LevelMatter::find($str);
    }

    public function cutting($str) {
      return CuttingSchoolYear::find($str);
    }

    public function getType() {
      return EvaluatedType::orderBy('id')->get();
    }

    public function subMatters() {
      return SubMatter::orderBy('id')->get();
    }

    public function evaluated($str) {
      return Evaluated::find($str);
    }

    public function existNote($evaluat) {
      $exists = Evaluat::where('evaluated_id', $evaluat)->exists();
      return $exists;
    }

    public function verifyClasse($classe, $matter) {
      $data = DB::table('classe_teachers as ct')
      ->join('get_classes as gc', 'gc.id', '=', 'ct.get_classe_id')
      ->join('level_matters as lm', 'lm.id', '=', 'ct.level_matter_id')
      ->join('matters as m', 'm.id', '=', 'lm.matter_id')
      ->where([
        'gc.school_year_id' => $this->year(),
        'gc.school_id' => $this->user->school_id,
        'ct.user_id' => $this->user->id,
        'ct.level_matter_id' => $matter,
        'ct.get_classe_id' => $classe
      ])
      ->first();
      return $data ? true:false;
    }


    public function getNotStudent($classe, $evaluat) {
      return $this->getStudentClasse($classe, $evaluat);
    }

    public function getEvaluated($matter, $classe) {
      $datas = DB::table('cutting_school_years as cs')
        ->leftJoin('evaluateds as e', function ($join) use ($classe, $matter) {
        $join->on('e.cutting_school_year_id', '=', 'cs.id')
        ->leftJoin('sub_matters as sm', 'sm.id', '=', 'e.sub_matter_id')
        ->where(['e.get_classe_id' => $classe, 'e.level_matter_id' => $matter]);
      })
      ->leftJoin('cuttings as c', 'c.id', '=', 'cs.cutting_id')
      ->leftJoin('evaluated_types as et', 'et.id', '=', 'e.evaluated_type_id')
      ->select(
        'cs.id', 'cs.status as actif', 'c.libelle as cutting', 'e.actif as status', 'e.id as id2',
        'et.libelle as libelle', 'sm.symbol as sub', 'e.value as value', 'e.created as date'
      )
      ->orderByRaw('cs.id, e.created')
      ->get()
      ->groupBy('id')
      ->map(function ($items) {
        return [
          'id' => $items->first()->id,
          'cutting' => $items->first()->cutting,
          'actif' => $items->first()->actif,
          'evaluated' => $items
          ->whereNotNull('libelle')
          ->map(fn ($item) => [
            'libelle' => $item->libelle,
            'status'  => $item->status,
            'value'  => $item->value,
            'date'  => $item->date,
            'sub'  => $item->sub,
            'id'  => $item->id2
          ])->values(),
        ];
      })
      ->values();
      return $datas ?? [];
    }


    public function getClasseUsers() {
      $compte = 0;
      return DataTables::of($this->getClasseElement())
      ->addColumn('compte', function() use (&$compte) {
        return (sprintf('%02d', $compte += 1));
      })
      ->addColumn('libelle', function ($row) {
        return ($row->libelle);
      })
      ->addColumn('effectif', function ($row) {
        return (sprintf('%02d', $row->inscrit).' / '.sprintf('%02d', $row->effectif));
      })
      ->addColumn('matters', function ($row) {
        return (ucwords($row->symbol));
      })
      ->addColumn('action', function ($row) {
        $url = route('evaluation.show',($row->classe.'_'.$row->matter));
        return ('<span class="card-block remove-label m-0 pb-0 text-center">
          <a href="'.$url.'" class="btn btn-sm btn-light btnView py-0 me-2 px-2">
          <i class="fas fa-ellipsis-h"></i>
          </a>
        </span>');
      })
      ->rawColumns(['compte', 'libelle', 'effectif', 'matters', 'action'])
      ->make(true);
    }


    public function getNoteEvaluated($classe, $evaluat) {
      $query = $this->getStudentClasse($classe, $evaluat);
      $compte = 0;
      return DataTables::of($query)
      ->addColumn('compte', function() use (&$compte) {
        return ($compte < 9 ? '0'.++$compte : ++$compte);
      })
      ->addColumn('matricule', function ($row) {
        return ($row->matricul);
      })
      ->addColumn('first', function ($row) {
        return (strtoupper($row->first));
      })
      ->addColumn('last', function ($row) {
        return (ucwords($row->last));
      })
      ->addColumn('genre', function ($row) {
        return ($row->genre == 'F' ? 'Feminin':'Masculin');
      })
      ->addColumn('note', function ($row) {
        return (
          $row->note == 'nc' ? $row->note:
          ($row->note ? $row->note:'--').' / '.(($row->value * 20) != 0 ? ($row->value * 20):'--')
        );
      })
      ->rawColumns(['compte', 'matricule', 'first', 'last', 'genre', 'note'])
      ->make(true);
    }

    public function getStudent($classe) {
      $query = DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->where('r.get_classe_id', $classe)
      ->select(['r.id', 's.matricul', 's.first', 's.last', 's.genre'])
      ->orderByRaw('s.first, s.last')
      ->get();
      return $query;
    }

    public function getStore($data) {
      $note = $data['value'] / self::NOTE;
      $dta = Evaluated::create([
        'value' => (string)$note,
        'created' => $data['date'],
        'sub_matter_id' => $data['sub'],
        'get_classe_id' => $data['classe'],
        'level_matter_id' => $data['matter'],
        'evaluated_type_id' => $data['type'],
        'cutting_school_year_id' => $data['cutting']
      ]);
      return $dta ? $dta->id:null;
    }


    public function getUpdate($data) {
      $evaluat = $this->Evaluated($data['evaluat']);
      if(!($evaluat['cutting_school_year']['status'] == 3)) {
        $note = $data['note'] / self::NOTE; 
        $evaluat->update([
          'value' => (string)$note,
          'created' => $data['date'],
          'sub_matter_id' => $data['subE'],
          'evaluated_type_id' => $data['type'],
          'actif' => $data['status'] ? '1':'0'
        ]);
        return true;
      }
      return false;
    }


    public function getDestroy($evaluated) {
      $data = $this->Evaluated($evaluated);
      if(!($data['cutting_school_year']['status'] == 3)) {
        $data->delete();
        return true;
      }
      return false;
    }


    private function getClasseElement() {
      return DB::table('classe_teachers as ct')
      ->join('get_classes as gc', 'gc.id', '=', 'ct.get_classe_id')
      ->join('level_matters as lm', 'lm.id', '=', 'ct.level_matter_id')
      ->join('matters as m', 'm.id', '=', 'lm.matter_id')
      ->where([
        'gc.school_year_id' => $this->year(),
        'gc.school_id' => $this->user->school_id,
        'ct.user_id' => $this->user->id
      ])
      ->select([
        'gc.id as classe', 'lm.id as matter', 'gc.libelle', 'gc.inscrit', 'gc.effectif', 'm.symbol'
      ])
      ->orderBy('gc.level_id')
      ->get();
    }

    private function getStudentClasse($classe, $evaluat) {
      return DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->leftJoin('evaluats as e', function ($join) use ($evaluat) {
        $join->on('e.register_id', '=', 'r.id')
        ->join('evaluateds as ev', 'ev.id', '=', 'e.evaluated_id')
        ->where('ev.id', $evaluat);
      })
      ->where('r.get_classe_id', $classe)
      ->select(['r.id', 'e.note', 'ev.value', 's.matricul', 's.first', 's.last', 's.genre'])
      ->orderByRaw('s.first, s.last')
      ->get();
    }

    private function year() {
      $year = SchoolYear::where('status', '1')->first();
      return $year ? $year->id:null;
    }
  }