<?php
  namespace App\Services;

  use App\Models\Serie;
  use App\Models\Level;
  use App\Models\School;
  use App\Models\GetClasse;
  use App\Models\SchoolYear;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;
  use Yajra\DataTables\Facades\DataTables;
  class ClasseService
  {
    private $schl;
    public function __construct() {
      $this->schl = Auth::user()->school_id ?? 1;
    }

    public function getYajra($str) {
      $query = DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->select([
        'ss.id', 's.matricul','s.first', 's.last', 's.genre', 's.date', 
        's.lieu', 'r.affecte', 'r.redoubant', 'r.boursier', 'r.lv2'
      ])
      ->where('r.get_classe_id', $str)->orderBy('s.first')->orderBy('s.last')->get();
      $compte = 0;
      return DataTables::of($query)
      ->addColumn('compte', function() use (&$compte) {
        return ($compte < 9 ? '0'.++$compte : ++$compte);
      })
      ->addColumn('matricul', function ($row) {
        return ($row->matricul);
      })
      ->addColumn('name', function ($row) {
        return (strtoupper($row->first).' '.ucwords($row->last));
      })
      ->addColumn('genre', function ($row) {
        return (ucwords($row->genre == 'F' ? 'Feminin':'Masculin'));
      })
      ->addColumn('affect', function ($row) {
        return (ucwords($row->affecte ? 'oui':'non'));
      })
      ->addColumn('redoublant', function ($row) {
        return (ucwords($row->redoubant ? 'oui':'non'));
      })
      ->addColumn('naissance', function ($row) {
        return (date('d.m.Y', strtotime($row->date)));
      })
      ->rawColumns(['compte', 'matricul', 'name', 'genre', 'naissance', 'affect', 'redoublant'])
      ->make(true);
    }

    
    public function getLevels() {
      $school = $this->school();
      $levels = Level::query()
      ->when($school['cycle1'], function ($q) use ($school) {
        $q->where('cycle1', $school['cycle1']);
      })
      ->when($school['cycle2'], function ($q) use ($school) {
        $q->orWhere('cycle2', $school['cycle2']);
      })
      ->orderBy('id')->get();
      return $levels ?? [];
    }


    public function level($str) {
      return Level::find($str) ?? null;
    }


    public function getClass($id) {
      $dts = GetClasse::where('level_id', $id)->where('school_id', $this->schl)->where('school_year_id', $this->year())->get();
      return $dts ?? [];
    }


    public function classe($str) {
      $dts = GetClasse::find($str);
      return $dts ?? null;
    }


    public function getSerie($str) {
      $dts = Serie::where($str, '1')->orderBy('id')->get();
      return $dts ?? [];
    }


    public function getStore($id, $number, $nbre, $lv2 = null, $serie = null) {
      $i = 0;
      while($i < $nbre) {
        GetClasse::create([
          'lv2' => $lv2,
          'level_id' => $id,
          'serie_id' => $serie,
          'effectif' => $number,
          'libelle' => $this->libClass($id, $serie),
          'school_year_id' => $this->year(),
          'school_id' => $this->schl,
        ]);
        $i++;
      }
    }


    public function update($data) {
      $dt = GetClasse::find($data['id']);
      if($dt['inscrit'] <= $data['number']) {
        $dt->update([
          'lv2' => $data['lv2'],
          'effectif' => $data['number'],
          'status' => $data['status'] ? '1':'0',
          'invalid' => ($dt['effecif'] > $dt['inscrit']) ? '1':'0'
        ]);
      }
    }


    public function delete($str) {
      $dts = GetClasse::find($str);
      if($dts['status']){
        return false;
      }
      $dts->delete();
      return true;
    }


    private function libClass($id, $serie = null) {
      $level = $this->level($id);
      $dt = $serie ? Serie::find($serie):null;
      $lib = $serie ? ((in_array($dt->libelle, ['A1', 'A2'])) ? 'A':$dt->libelle):null;
      $nbre = $this->count($id, ( $serie ? (in_array($lib, ['A1', 'A2']) ? 1:$serie):null));
      return $level['symbol'].$lib.$nbre;
    }


    private function count($id, $serie = null) {
      $query = GetClasse::where('level_id', $id)
      ->where('school_id', $this->schl)
      ->where('school_year_id', $this->year());

      in_array($serie, [1, 2, 3])
      ? $query->whereIn('serie_id', [1, 2, 3])
      : $query->where('serie_id', $serie);
      return $query->count() + 1;
    }


    private function year() {
      $year = SchoolYear::where('status', '1')->first();
      return $year ? $year->id:null;
    }


    private function school() {
      return School::find($this->schl) ?? null;
    }
  }