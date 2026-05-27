<?php

namespace App\Exports;

use App\Services\MoyenneService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class File2Export implements FromView
{
    protected $classe, $libelle, $matter, $cutting;
    public function __construct($classe, $libelle, $matter, $cutting)
    {
        $this->classe = $classe;
        $this->libelle = $libelle;
        $this->matter = $matter;
        $this->cutting = $cutting;
    }

    public function view(): View
    {
        $service = app(MoyenneService::class);

        return view('exports.file_2', [
            'libelle' => $this->libelle,
            'matter' => $this->matter,
            'cutting' => $this->cutting,
            'data' => $service->getStudent($this->classe)
        ]);
    }
}
