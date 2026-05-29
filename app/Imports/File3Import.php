<?php

namespace App\Imports;

use App\Services\MoyenneService;
use Illuminate\Support\Collection;
use App\Jobs\MoyenneImportMatterJob;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Failure;

class File3Import implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected $str;
    public function __construct($str)
    {
        $this->str = $str;
    }
    
    public function collection(Collection $data)
    {
        list($matter, $cutting, $classe) = explode('_', $this->str);
        $service = app(MoyenneService::class);
        $table = [];

        foreach($data as $item) {
            $student = $service->studentId($item['matricule'], $classe);
            if($student) {
                $table[] = [
                    'id' => $student->id,
                    'genre' => $student->genre,
                    'moyen' => $this->format($item['moyenne'])
                ];
            }
        }

        // Déclenchement de job pour le calcul de moyenne
        MoyenneImportMatterJob::dispatch($table, $matter, $cutting, $classe);
    }

    public function rules(): array
    {
        return [
            '*.matricule' => 'required|string|size:9',
            '*.nom' => 'required|string',
            '*.prenoms' => 'required|string',
            '*.genre' => 'required|string',
            '*.n' => 'required|string',
            '*.moyenne' => 'nullable|numeric',
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
        $value = (float) $value;
        $formatted = $value > 0 ? number_format($value, 2, '.', '') : (string) $value;
        return $value < 10 ? '0' . $formatted : $formatted;
    }
}
