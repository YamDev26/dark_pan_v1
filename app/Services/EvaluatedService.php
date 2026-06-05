<?php
  namespace App\Services;

  use App\Models\Evaluated;
  use App\Models\SubMatter;
  use App\Models\GetClasse;
  use App\Models\SchoolYear;
  use App\Models\LevelMatter;
  use App\Models\EvaluatedType;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;
  use Yajra\DataTables\Facades\DataTables;

  class EvaluatedService
  {
    private const NOTE  = 20;
    private $schl;
    public function __construct() {
      $this->schl = Auth::user()->school_id ?? 1;
    }


    public function getClasse() {
      $query = GetClasse::where('school_id', $this->schl)
      ->where('school_year_id', $this->year())->where('status', '1')
      ->orderBy('id')->orderBy('level_id')->get();

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
          <button type="button" class="btn btn-sm btn-warning text-white py-0 me-2 px-2" data-id="'.$row->id.'">
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

    public function getType() {
      return EvaluatedType::orderBy('id')->get();
    }

    public function getMatters($level, $serie = null) {
      $data = DB::table('level_matters as lm')
      ->join('matters as m', 'm.id', '=', 'lm.matter_id')
      ->select( 'lm.id', 'm.libelle', 'm.symbol', 'lm.value')
      ->where(['lm.level_id' => $level, 'lm.serie_id' => $serie])
      ->where('m.libelle', '!=', 'conduite')
      ->where('lm.school_id', $this->schl)
      ->orderByRaw(' m.bilan_matter_id, m.position')
      ->get();
      return $data ?? [];
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

    private function year() {
      $year = SchoolYear::where('status', '1')->first();
      return $year ? $year->id:null;
    }
  }