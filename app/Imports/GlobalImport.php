<?php

namespace App\Imports;

use App\Services\MoyenneService;
use Illuminate\Support\Collection;
use App\Events\MoyenneImportGlobalEvent;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class GlobalImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected $str, $user;
    public function __construct($str, $user)
    {
        $this->str = $str;
        $this->user = $user;
    }


    public function collection(Collection $data)
    {
        $service = app(MoyenneService::class);
        list($class, $cutting) = explode('_', $this->str);
        $classe = $service->getClasse($class);
        
        $matters = $service->getMatters($class);
        $matieres = $classe->level_id < 5
        ? array_merge($service->getSubMatter(), json_decode($matters->where('libelle', '!=', 'Français'), true))
        : json_decode($matters, true);
        $resultat = [];

        foreach($matieres as $matiere) {
            foreach($data as $row) {
                $resutl = $service->studentId($row['matricule'], $class);
                $resultat[$matiere['id'].'_'.$matiere['symbol']][] = [
                    'id'    => $resutl->id,
                    'genre' => $resutl->genre,
                    'moyen' => $this->format($row[strtolower($matiere['symbol'])]),
                ];
            }
        }

        MoyenneImportGlobalEvent::dispatch(
            $resultat, $classe, $cutting, $this->user
        );
    }


    public function rules(): array
    {
        return [
            '*.matricule' => 'required|string|size:9',
            '*.nom' => 'required|string',
            '*.prenoms' => 'required|string',
            '*.genre' => 'required|string',
            '*.n' => 'required|numeric',
        ];
    }


    private function format($moyen) {
        if (blank($moyen)) {
            return 'nc';
        }
        $value = str_replace([' ', ','], ['', '.'], $moyen);
        if (!is_numeric($value)) {
            return 'nc';
        }
        return (float) $value === 20.0
        ? '20'
        : sprintf('%05.2f', $value);
    }
}
