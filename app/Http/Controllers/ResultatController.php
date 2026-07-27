<?php

namespace App\Http\Controllers;

use App\Services\ResultatService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;


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

    
    public function generete(Request $request, string $str)
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
            list($classe, $cutting) = explode('_', $str);

            $value = $this->definirCutting($cutting);
            
            $resultat = $this->resultat($valide['student'], $str); 

            $pdf = Pdf::loadView('pdf.bulletins.index_'.$value,[
                'resultat' => $resultat
            ]);

            return $pdf->stream('bulletin_scolaire_'.$classe.'.pdf');
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'.$e->getMessage()
            ]);
        }
    }



    private function resultat($student, $str)
    {
        $table = [];
        list($classeId, $cuttingId) = explode('_', $str);
        $classe = $this->service->getClasse($classeId);
        $cutting = $this->service->getCutting($cuttingId);
        $result = $this->service->getResultatClasse($classeId, $cuttingId);

        foreach($student as $item) {
            $string = mt_rand(100, 999);
            if($item) {
                $table[] = [
                    'result' => $result,
                    'string' => $string,
                    'classe' => $classe,
                    'cutting' => $cutting,
                    'school' => $this->service->school(),
                    'qrCode' => $this->qrcode($string, $cutting),
                    'bilans' => $this->service->getMoyenneBilan($item, $cuttingId),
                    'student' => $this->service->getStudent($item, $classeId, $cuttingId),
                    'matters' => $this->service->getMoyenneMatters($item, $cuttingId, $classe),
                    'sunMatter' => $classe->level_id < 5 ? $this->service->getMoyenneSubMatter($item, $cuttingId):null,
                ];
            }
        }
        return $table;
    }


    private function qrcode($string, $cutting)
    {
        $school = $this->service->school();
        $renderer = new ImageRenderer(
            new RendererStyle(100),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);
        $png = base64_encode($writer->writeString(
            $string.'-'.$school->id
            .' ~ '.ucwords($cutting->cutting->libelle)
            .' ~ '.ucwords($school->name) 
            
        ));
        return ('data:image/png;base64,'.$png);
    }


    private function definirCutting($cuttingId)
    {
        $cutting = $this->service->getCutting($cuttingId);
        $libelle = explode(' ', $cutting->cutting->libelle);
        return $libelle[1];
    }
    
}

