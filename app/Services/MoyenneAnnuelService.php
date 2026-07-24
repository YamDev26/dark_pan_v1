<?php
  namespace App\Services;

  use App\Models\MoyenneAnnulSub;
  use App\Models\MoyenneAnnuelle;
  use App\Models\CuttingSchoolYear;
  use App\Models\MoyenneAnnulBilan;
  use App\Models\MoyenneAnnulMatter;
  use Illuminate\Support\Facades\DB;

  class MoyenneAnnuelService
  {
  
    public function storeMoyenneMatter($classe, $matter, $cutting) {
      $data = ClassementStudent(
        $this->getMoyenneAnnuelleMatters($classe, $matter, $cutting)
      );
      foreach($data as $item) {
        MoyenneAnnulMatter::updateOrCreate([
            'register_id' => $item['id'],
            'level_matter_id' => $matter,
          ], [
            'moyenne' => $item['moyen'],
            'rang' => $item['rang']
          ]
        );
      }
    }


    public function storeMoyenneSub($classe, $matter, $cutting) {
      $data = ClassementStudent(
        $this->getMoyenneAnnuelleSub($classe, $matter, $cutting)
      );
      foreach($data as $item) {
        MoyenneAnnulSub::updateOrCreate([
            'register_id' => $item['id'],
            'sub_matter_id' => $matter,
          ], [
            'moyenne' => $item['moyen'],
            'rang' => $item['rang']
          ]
        );
      }
    }


    public function storeMoyenneBilan($classe, $bilan, $cutting) {
      $data = ClassementStudent(
        $this->getMoyenneAnnuelleBilan($classe, $bilan, $cutting)
      );
      foreach($data as $item) {
        MoyenneAnnulBilan::updateOrCreate([
            'register_id' => $item['id'],
            'bilan_matter_id' => $bilan,
          ], [
            'moyenne' => $item['moyen'],
            'rang' => $item['rang']
          ]
        );
      }
    }


    public function storeMoyenne($classe,  $cutting) {
      $yearId = $this->cuttingYear($cutting);
      $data = ClassementStudent(
        $this->getMoyenneAnnuelle($classe, $yearId)
      );
      foreach($data as $item) {
        MoyenneAnnuelle::updateOrCreate([
            'register_id' => $item['id'],
            'school_year_id' => $yearId,
          ], [
            'moyenne' => $item['moyen'],
            'rang' => $item['rang']
          ]
        );
      }
    }


    private function getMoyenneAnnuelleMatters($classe, $matter, $cutting) {
      $yearId = $this->cuttingYear($cutting);
      return DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->join('get_classes as gc', 'gc.id', '=', 'r.get_classe_id')
      ->join('moyenne_matters as mm', 'mm.register_id', '=', 'r.id')
      ->join('cutting_school_years as csy', 'csy.id', '=', 'mm.cutting_school_year_id')
      ->join('cuttings as c', 'c.id', '=', 'csy.cutting_id')
      ->where('r.get_classe_id', $classe)
      ->where('gc.school_year_id', $yearId)
      ->where('mm.level_matter_id', $matter)
      ->where('mm.moyenne', '<>', 'nc')
      ->select(
        'ss.id', 's.genre',
        DB::raw('ROUND(SUM(mm.moyenne * c.value) / SUM(c.value), 2) as moyen')
      )
      ->groupBy('ss.id', 's.genre')
      ->get()
      ->map(fn ($item) => (array) $item)
      ->toArray();
    }

    
    private function getMoyenneAnnuelleSub($classe, $matter, $cutting) {
      $yearId = $this->cuttingYear($cutting);
      return DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->join('get_classes as gc', 'gc.id', '=', 'r.get_classe_id')
      ->join('moyenne_sub_matters as mm', 'mm.register_id', '=', 'r.id')
      ->join('cutting_school_years as csy', 'csy.id', '=', 'mm.cutting_school_year_id')
      ->join('cuttings as c', 'c.id', '=', 'csy.cutting_id')
      ->where('r.get_classe_id', $classe)
      ->where('gc.school_year_id', $yearId)
      ->where('mm.sub_matter_id', $matter)
      ->where('mm.moyenne', '<>', 'nc')
      ->select(
        'ss.id', 's.genre',
        DB::raw('ROUND(SUM(mm.moyenne * c.value) / SUM(c.value), 2) as moyen')
      )
      ->groupBy('ss.id', 's.genre')
      ->get()
      ->map(fn ($item) => (array) $item)
      ->toArray();
    }


    private function getMoyenneAnnuelleBilan($classe, $bilan, $cutting) {
      $yearId = $this->cuttingYear($cutting);
      return DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->join('get_classes as gc', 'gc.id', '=', 'r.get_classe_id')
      ->join('moyenne_bilans as mb', 'mb.register_id', '=', 'r.id')
      ->join('cutting_school_years as csy', 'csy.id', '=', 'mb.cutting_school_year_id')
      ->join('cuttings as c', 'c.id', '=', 'csy.cutting_id')
      ->where('r.get_classe_id', $classe)
      ->where('gc.school_year_id', $yearId)
      ->where('mb.bilan_matter_id', $bilan)
      ->where('mb.moyenne', '<>', 'nc')
      ->select(
        'ss.id', 's.genre',
        DB::raw('ROUND(SUM(mb.moyenne * c.value) / SUM(c.value), 2) as moyen')
      )
      ->groupBy('ss.id', 's.genre')
      ->get()
      ->map(fn ($item) => (array) $item)
      ->toArray();
    }


    private function getMoyenneAnnuelle($classe, $yearId) {
      return DB::table('registers as r')
      ->join('school_students as ss', 'ss.id', '=', 'r.school_student_id')
      ->join('students as s', 's.id', '=', 'ss.student_id')
      ->join('get_classes as gc', 'gc.id', '=', 'r.get_classe_id')
      ->join('moyenne_trimestres as mt', 'mt.register_id', '=', 'r.id')
      ->join('cutting_school_years as csy', 'csy.id', '=', 'mt.cutting_school_year_id')
      ->join('cuttings as c', 'c.id', '=', 'csy.cutting_id')
      ->where('r.get_classe_id', $classe)
      ->where('gc.school_year_id', $yearId)
      ->where('mt.moyenne', '<>', 'nc')
      ->select(
        'ss.id', 's.genre',
        DB::raw('ROUND(SUM(mt.moyenne * c.value) / SUM(c.value), 2) as moyen')
      )
      ->groupBy('ss.id', 's.genre')
      ->get()
      ->map(fn ($item) => (array) $item)
      ->toArray();
    }


    private function cuttingYear($cutting) {
      $dts = CuttingSchoolYear::find($cutting);
      return $dts ? $dts['school_year_id']:null;
    }
  }