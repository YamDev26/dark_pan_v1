<?php

namespace App\Http\Controllers;

use App\Services\ResultatService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ResultatController extends Controller
{
    protected $service;

    public function __construct(ResultatService $service)
    {
        $this->service = $service;
    }
    
    public function index()
    {
        try {
            return view('pages.resultats.index',[
                'cutting' => $this->service->getCuttings()
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function dataTableClasse()
    {
        try {
            return $this->service->getDataTableClasse();
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function show(string $str)
    {
        try {
            list($classe, $cutting) =explode('_', $str);
            $matieres = $this->service->getMatters($classe);
            $tauxMatter = $this->service->resultatMatter($classe, $cutting);
            return view('pages.resultats.detail',[
                'matieres' => $matieres,
                'resultmatters' => $tauxMatter,
                'classe' => $this->service->getClasse($classe),
                'cutting' => $this->service->getCutting($cutting),
                'result' => $this->service->getResultatClasse($classe, $cutting),
                'tranche' => $this->service->getResultatTranche($classe, $cutting)
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function edit(string $str)
    {
        try {
            list($classe, $cutting) = explode('_', $str);
            $data = $this->service->studentMoyenneList(
                $classe, $cutting
            );

            return view('pages.resultats.show',[
                'data' => $data,
                'classe' => $this->service->getClasse($classe),
                'cutting' => $this->service->getCutting($cutting),
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function generete(Request $request, string $id)
    {
        try {
            
            $valide = $request->validate([
                'student' => 'required|array',
                'student.*' => 'required|string',
            ]);

            if (!collect($valide['student'])->filter()->isNotEmpty()) {
                return back()->with([
                    'str' => 'danger',
                    'msg' => 'Aucun élève selectionné !'
                ]);
            }

            $pdf = Pdf::loadView('pdf.bulletins.index_1');
            return $pdf->stream('bulletin.pdf');
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
}

