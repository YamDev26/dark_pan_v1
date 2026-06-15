<?php

namespace App\Imports;

use App\Jobs\MoyenneEditJob;
use App\Services\MoyenneService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

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
        list($classe, $matter, $cutting) = explode('_', $this->str);
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

        $classe = $service->getClasse($classe);
        // Déclenchement de job pour le calcul de moyenne
        MoyenneEditJob::dispatch($table, $matter, $cutting, $classe);
    }

    public function rules(): array
    {
        return [
            '*.matricule' => 'required|string|size:9',
            '*.nom' => 'required|string',
            '*.prenoms' => 'required|string',
            '*.genre' => 'required|string',
            '*.n' => 'required|numeric',
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
        return (float) $value === 20.0
        ? '20'
        : sprintf('%05.2f', $value);
    }
}
