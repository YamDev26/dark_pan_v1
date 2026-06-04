<?php

namespace App\Exports;

use App\Services\GestionNoteService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class NoteExport implements FromView
{
    protected $classe, $evaluat;
    public function __construct($classe, $evaluat)
    {
        $this->classe = $classe;
        $this->evaluat = $evaluat;
    }
    
    public function view(): View
    {
        $service = app(GestionNoteService::class);

        return view('exports.file_3', [
            'evaluat' => $service->evaluated($this->evaluat),
            'data' => $service->getStudent($this->classe)
        ]);
    }
}
