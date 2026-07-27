<?php
  namespace App\Services;

  use App\Models\School;
  use App\Models\SlotTime;
  use App\Models\DrenSchool;
  class SettingService
  {
    private $schl;
    public function __construct() {
      $user = getUserGlobal();
      $this->schl = $user ? $user->school_id:null;
    }
    
    public function school() {
      return School::find($this->schl);
    }


    public function getDren() {
      $dts = DrenSchool::orderBy('id')->get();
      return $dts ?? [];
    }

    public function update($str, $data, $path = null) {
      School::where('id', $str)->update([
        'code' => $data['code'],
        'autorisation' => $data['num'],
        'name' => strtolower($data['name']),
        'slug' => strtolower($data['slug']),
        'email' => $data['email'],
        'dren_school_id' => $data['dren'],
        'phon' => $data['phon'],
        'city' => strtolower($data['ville']),
        'addres' => $data['address'],
        'created' => $data['created'],
        'opening' => $data['opening'],
        'cycle1' => $data['cycle1'] ? true:false,
        'cycle2' => $data['cycle2'] ? true:false,
        'date1' => $data['date1'],
        'date2' => $data['date2'],
        'etat' => $data['etat'],
        'caisse'  => $data['caisse'] == 'oui' ? true:false,
        'notes'  => $data['note'] == 'oui' ? true:false,
        'informatik'  => $data['informatik'] == 'oui' ? true:false,
        'autres'  => $data['autres'] == 'oui' ? true:false,
        'logo' => $path,
        'param' => true
      ]);
    }


    public function slotTime() {
      return [
        'dt1' => SlotTime::where('school_id', $this->schl)->where('period', 1)->orderBy('order')->get(),
        'dt2' => SlotTime::where('school_id', $this->schl)->where('period', 2)->orderBy('order')->get()
      ];
    }

    public function storeSlot($dbt1, $dbt2, $fin1, $fin2): bool {
      $slots = [
        1 => [$dbt1, $fin1],
        2 => [$dbt2, $fin2],
      ];
      foreach ($slots as $index => [$debut, $fin]) {
        $this->storeTime($debut, $fin, $index);
      }
      return true;
    }


    public function getSolt($str) {
      $dts = SlotTime::find($str);
      return $dts ?? null;
    }


    public function updateSol($dbt, $fin, $str) {
      return SlotTime::where('id', $str)->update([
        'dbt' => $dbt,
        'fin' => $fin,
      ]);
    }


    private function storeTime($dbt, $fin, $str) {
      SlotTime::where('school_id', $this->schl)->where('period', $str)->delete();
      $data = [];
      foreach ($dbt as $index => $debut) {
        $end = $fin[$index] ?? null;
        if (!empty($debut) && !empty($end)) {
          $data[] = [
            'dbt'       => $debut,
            'fin'       => $end,
            'order'     => $index + 1,
            'period'    => $str,
            'school_id' => $this->schl,
          ];
        }
      }
      if (!empty($data)) {
        SlotTime::insert($data);
      }
    }

  }