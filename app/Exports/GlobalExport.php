<?php

namespace App\Exports;

use App\Services\MoyenneService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class GlobalExport implements FromView
{
    protected $classe,  $cutting;
    public function __construct($classe, $cutting)
    {
        $this->classe = $classe;
        $this->cutting = $cutting;
    }


    public function view(): View
    {
        $service = app(MoyenneService::class);

        $matters = $service->getMatters($this->classe->id);
        $matieres = $this->classe->level_id < 5
        ? array_merge($service->getSubMatter(), json_decode($matters->where('libelle', '!=', 'Français'), true))
        : json_decode($matters, true);
        $datas = $service->getStudent($this->classe->id);



        return view('exports.file_moyenne',[
            'cutting' => $this->cutting,
            'classe' => $this->classe,
            'matters' => $matieres,
            'datas' => $datas,
        ]);
    }
}
