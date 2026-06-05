<?php

namespace App\Imports;

use App\Services\GestionNoteService;
use Illuminate\Support\Collection;
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
        $nots = 'nc';
        if(!blank($note)){
            if(is_int($note)){
                $nots = ($note <= $valeur) ? ($note < 10 ? '0'.$note:$note):'nc';
            }
            else{
                $not = str_replace([' ', ','], ['', '.'], $note);
                $nots = floatval($not) ? 
                (($note <= $valeur) ? 
                ($note < 10 ? '0'.$note:$note):'nc'):
                'nc';
            }
        }
        return $nots;
    }
}
