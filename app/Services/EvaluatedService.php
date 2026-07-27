<?php
  namespace App\Services;

  use App\Models\Evaluated;
  use App\Models\SubMatter;
  use App\Models\GetClasse;
  use App\Models\SchoolYear;
  use App\Models\LevelMatter;
  use App\Models\EvaluatedType;
  use App\Models\CuttingSchoolYear;
  use Illuminate\Support\Facades\DB;
  use Yajra\DataTables\Facades\DataTables;

  class EvaluatedService
  {
    private const NOTE  = 20;
    private $schl;
    public function __construct() {
      $user = getUserGlobal();
      $this->schl = $user ? $user->school_id:null;
    }


    public function getClasse() {
      $query = GetClasse::where('school_id', $this->schl)
      ->where('school_year_id', $this->year())->where('status', '1')
      ->orderBy('level_id')->orderBy('id')->get();

      $compte = 0;
      return DataTables::of($query)
      ->addColumn('compte', function() use (&$compte) {
        return ($compte < 9 ? '0'.++$compte : ++$compte);
      })
      ->addColumn('libelle', function ($row) {
        return ($row->libelle);
      })
      ->addColumn('effectif', function ($row) {
        return (($row->inscrit < 10 ? '0'.$row->inscrit:$row->inscrit).' / '.$row->effectif);
      })
      ->addColumn('action', function ($row) {
        return ('<span class="card-block remove-label m-0 pb-0 text-center">
          <button type="button" class="btn btn-sm btn-outline-light btnView py-0 me-2 px-2" data-id="'.$row->id.'">
          <i class="fas fa-ellipsis-h"></i>
          </button>
        </span>');
      })
      ->rawColumns(['compte', 'libelle', 'effectif', 'action'])
      ->make(true);
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

    public function getMatters($level, $serie = null) {
      return DB::table('level_matters as lm')
      ->join('matters as m', 'm.id', '=', 'lm.matter_id')
      ->select(
        'lm.id',
        'm.libelle',
        'm.symbol',
        'lm.value'
      )
      ->where('lm.school_id', $this->schl)
      ->where('lm.level_id', $level)
      ->when(
        $serie !== null,
        fn ($query) => $query->where('lm.serie_id', $serie),
        fn ($query) => $query->whereNull('lm.serie_id')
      )
      ->where('m.libelle', '!=', 'conduite')
      ->orderBy('m.bilan_matter_id')
      ->orderBy('m.position')
      ->get();
    }


    public function subMatters() {
      return SubMatter::orderBy('id')->get();
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

    public function evaluated($str) {
      return Evaluated::find($str);
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


    public function update($data) {
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

    public function destroy($evaluated) {
      $data = $this->Evaluated($evaluated);
      if(!($data['cutting_school_year']['status'] == 3)) {
        $data->delete();
        return true;
      }
      return false;
    }

    public function getStudentMoyenneMatter($classe, $matter, $cutting) {
      return DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->leftJoin('moyenne_matters as mm', function ($join) use ($matter, $cutting) {
        $join->on('mm.register_id', '=', 'r.id')
        ->where('mm.level_matter_id', $matter)
        ->where('mm.cutting_school_year_id', $cutting);
      }) 
      ->select([ 
        'r.id', 's.matricul', 's.first', 's.last', 's.genre','mm.moyenne',
      ])
      ->where('r.get_classe_id', $classe)
      ->orderByRaw('s.first, s.last')
      ->get();
    }


    public function getMoyennefresh($classe, $matter, $cutting) {
      return DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->leftJoin('moyenne_matters as mm', function ($join) use ($cutting, $matter) {
        $join->on('mm.register_id', '=', 'r.id')
        ->where('mm.cutting_school_year_id', $cutting)
        ->where('mm.level_matter_id', $matter);
      })
      ->leftJoin('moyenne_sub_matters as msm', function ($join) use ($cutting, $matter) {
        $join->on('msm.register_id', '=', 'r.id')
        ->where('msm.cutting_school_year_id', $cutting);
      })
      ->where('r.get_classe_id', $classe)
      ->groupBy('r.id', 's.matricul', 's.first', 's.last', 's.genre', 'mm.moyenne', 'mm.rang')
      ->select('r.id', 's.matricul', 's.first', 's.last', 's.genre', 'mm.moyenne', 'mm.rang',
        DB::raw('MAX(CASE WHEN msm.sub_matter_id = 1 THEN msm.moyenne END) as cf'),
        DB::raw('MAX(CASE WHEN msm.sub_matter_id = 2 THEN msm.moyenne END) as og'),
        DB::raw('MAX(CASE WHEN msm.sub_matter_id = 3 THEN msm.moyenne END) as eo')
      )->get();
    }

    private function year() {
      $year = SchoolYear::where('status', '1')->first();
      return $year ? $year->id:null;
    }
  }