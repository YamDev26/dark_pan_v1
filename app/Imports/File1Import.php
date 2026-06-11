<?php

namespace App\Imports;

use App\Services\StudentService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;
class File1Import implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function collection(Collection $data)
    {
        $service = app(StudentService::class);

        foreach($data as $item) {
            $date = $this->dates($item['date_naissance']);
            $data = [
                'matricul' => $item['matricule'],
                'first' => $item['nom'],
                'last' => $item['prenom'],
                'genre' => $this->getGenre($item['genre']),
                'date' => $date->format('Y-m-d'),
                'lieu' => $item['lieu_naissance'],
                'nation' => $item['nationalite'],
                'nom' => $item['nom_parent'],
                'prenom' => $item['prenom_parent'],
                'civilit' => $this->getSexe($item['sexe_parent']),
                'telephon' => $this->getPhon($item['telephone']),
                'type' => $this->getType($item['type_parent']),
                'residence' => $item['residence'],
                'email' => null
            ];
            $service->getStore($data);
        }
    }


    public function rules(): array
    {
        return [
            '*.matricule' => 'required|string|size:9',
            '*.nom' => 'required|string',
            '*.prenom' => 'required|string',
            '*.genre' => 'required|string',
            '*.date_naissance' => 'required|integer',
            '*.lieu_naissance' => 'required|string',
            '*.nationalite' => 'required|string',
            '*.nom_parent' => 'required|string',
            '*.prenom_parent' => 'required|string',
            '*.sexe_parent' => 'required|string',
            '*.type_parent' => 'required|string',
            '*.telephone' => 'required|numeric',
            '*.residence' => 'required|string',
        ];
    }
    

    private function getGenre($valuer) {
        $str = strtolower($valuer);
        if(str_contains($str, 'f')) {
            return 'F';
        }
        return 'M';
    }


    private function dates($value, string $format = 'Y-m-d'): ?Carbon {
        if (empty($value)) {
            return null;
        }
        try {
            // Date Excel numérique
            if (is_numeric($value)) {
                return Carbon::instance(
                    ExcelDate::excelToDateTimeObject($value)
                );
            }
            // Format personnalisé
            return Carbon::createFromFormat($format, trim($value));

        } catch (\Throwable $e) {
            // Fallback automatique
            try {
                return Carbon::parse($value);
            } catch (\Throwable $e) {
                return null;
            }
        }
    }


    private function getPhon($phon) {
        $count = strlen((string) abs($phon));
        return $count == 10 ? $phon:'0'.$phon;
    }


    private function getSexe($sexe) {
        $str = strtolower($sexe);
        if(str_contains($str, 'f')) {
            return 'Mde';
        }
        return 'Mr';
    }


    private function getType($libelle) {
        $str = strtolower($libelle);
        if(($str == 'père') || ($str == 'mère') || ($str == 'parent')) {
            return 'parent';
        }
        return 'tuteur';
    }
}
