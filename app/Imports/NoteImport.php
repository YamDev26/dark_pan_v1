<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Services\GestionNoteService;
use App\Jobs\Matters\CalculMoyenneJob;
use App\Jobs\Matters\CalculSubMoyenneJob;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class NoteImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected $str;
    public function __construct($str)
    {
        $this->str = $str;
    }


    public function collection(Collection $data)
    {
        $service = app(GestionNoteService::class);
        $evaluat = $service->evaluated($this->str);

        foreach($data as $item) {

            $id = $service->studentId($item['matricule'], $evaluat['get_classe_id']);
            
            if($id) {
                $note = $this->valNote($item['note'], ($evaluat['value'] * 20));
                $service->noteEvaluat($id->id, $this->str, $note);
            }
        }

        // Déclenchement de job pour le calcul de moyenne
        if(! $evaluat['sub_matter_id']) {
            CalculMoyenneJob::dispatch(
                $evaluat['get_classe_id'], 
                $evaluat['level_matter_id'], 
                $evaluat['cutting_school_year_id']
            );
        }
        else {
            CalculSubMoyenneJob::dispatch(
                $evaluat['get_classe_id'], 
                $evaluat['level_matter_id'], 
                $evaluat['cutting_school_year_id'],
                $evaluat['sub_matter_id']
            );
        }
    }


    public function rules(): array
    {
        return [
            '*.matricule' => 'required|string|size:9',
            '*.nom' => 'required|string',
            '*.prenoms' => 'required|string',
            '*.genre' => 'required|string',
            '*.n' => 'required|numeric',
            '*.note' => 'nullable|numeric',
        ];
    }


    private function valNote($note, $valeur): string
    {
        if (blank($note)) {
            return 'nc';
        }

        $note = str_replace([' ', ','], ['', '.'], trim($note));

        if (!is_numeric($note)) {
            return 'nc';
        }

        $note = (float) $note;

        if ($note < 0 || $note > (20 * $valeur)) {
            return 'nc';
        }
        return $note < 10 ? '0' . $note : (string) $note;
    }
}
