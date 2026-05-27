<?php

namespace App\Imports;

use App\Services\RegisterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Failure;

class File2Import implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private $class, $lv2;
    public function __construct($class, $lv2 = null)
    {
        $this->class = $class;
        $this->lv2 = $lv2;
    }


    public function collection(Collection $data)
    {
        $service = app(RegisterService::class);

        foreach($data as $item) {

            $dta = $service->search($item['matricule']);
            if($dta && !$dta['class']) {
                $service->getStore(
                    $dta['student']->id,
                    $this->class,
                    $this->strToLowes($item['affecte']),
                    $this->strToLowes($item['redoublant']),
                    $this->strToLowes($item['boursier']),
                    $this->strToLowes($item['interne']),
                    $this->lv2 ? $this->getLv2($item['lv2']):null,
                );
            }
        }
    }


    public function rules(): array
    {
        return [
            '*.matricule' => 'required|string|size:9',
            '*.nom' => 'required|string',
            '*.prenom' => 'required|string',
            '*.genre' => 'required|string',
            '*.affecte' => 'required|string',
            '*.redoublant' => 'required|string',
            '*.boursier' => 'required|string',
            '*.interne' => 'required|string',
            '*.lv2' => 'nullable|string',
        ];
    }


    public function getLv2($lv2) {
        if(strtolower($lv2) == 'allemand') {
            return 'all';
        }
        return 'esp';
    }

    public function strToLowes($liblle) {
        return strtolower($liblle);
    }
}
