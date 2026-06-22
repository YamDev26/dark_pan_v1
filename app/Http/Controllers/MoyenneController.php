<?php

namespace App\Http\Controllers;

use App\Exports\File2Export;
use App\Imports\File3Import;
use App\Exports\GlobalExport;
use App\Imports\GlobalImport;
use App\Imports\FileFrenshImport;
use App\Services\MoyenneService;
use App\Events\MoyenneEditEvent;
use App\Events\NonClasseStudentEvent;
use App\Events\MoyenneEditFrenshEvent;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MoyenneController extends Controller
{
    protected $service;
    function __construct(MoyenneService $service) {
        $this->service = $service;
    }

    
    public function index()
    {
        try {
            return view('pages.moyennes.index');
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function dataTable()
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

    public function create(string $str)
    {
        try {
            list($classe, $matter, $cutting) = explode('_', $str);
            $matters = $this->service->getMatter($matter);
            $verif = ($matters['matter']['id'] === 2 && $matters['level_id'] < 5) ? true:false;
            $data = $verif ?  $this->service->getStudentMoyenneFrensh($classe, $matter, $cutting):
            $this->service->getStudentMoyenne($classe, $matter, $cutting);
            return view('pages.moyennes.create_'.($verif ? 2:1),[
                'datas' => $data,
                'matter' => $matters,
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

    
    public function store(Request $request, string $str)
    {
        try {
            $valide = $request->validate([
                'students' => 'required|array',
                'students.*' => 'required|string',
                'moyen1' => 'required|array',
                'moyen1.*' => 'nullable|numeric',
                'moyen2' => 'nullable|array',
                'moyen2.*' => 'nullable|numeric',
                'moyen3' => 'nullable|array',
                'moyen3.*' => 'nullable|numeric',
                'string'  => 'required|string',
                'frensh'  => 'required|string',
            ]);

            if((!$valide['string'] == $str)) {
                return back()->with([
                    'str' => 'danger',
                    'msg' => 'Erreur, Incompactibilité entres les informations !'
                ]);
            }
            $valide['frensh'] == 'oui' ?
            MoyenneEditFrenshEvent::dispatch($valide['students'], $valide['moyen1'], $valide['moyen2'], $valide['moyen3'], $str):
            MoyenneEditEvent::dispatch($valide['students'], $valide['moyen1'], $str);
            return to_route('moyenne.list', $str)->with([
                'str' => 'success',
                'msg' => 'Validation réussie. En attente des traitement !'
            ]);
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
            list($class, $cutting) = explode('_', $str);
            $classe = $this->service->getClasse($class);
            $matters = $this->service->getMatters($class);
            $matieres = $classe['level_id'] < 5
            ? array_merge($this->service->getSubMatter(), json_decode($matters, true))
            : json_decode($matters, true);
            return view('pages.moyennes.detail',[
                'classe' => $classe,
                'matters' => $matters,
                'matieres' => $matieres,
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


    public function tableData(string $str)
    {
        try {
            list($class, $cutting) = explode('_', $str); 
            $classe = $this->service->getClasse($class);
            return $this->service->getMoyenneMCuttingClasse(
                $classe['level_id'], $class, $cutting, $classe['serie_id']
            );
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function moyenne(string $str)
    {
        try {
            list($classe, $matter, $cutting) = explode('_', $str);
            $matters = $this->service->getMatter($matter);
            $verif = ($matters['matter']['id'] === 2 && $matters['level_id'] < 5) ? true:false;
            return view('pages.moyennes.show_'.($verif ? 2:1),[
                'matter' => $matters,
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


    public function autres(string $str) 
    {
        try {
            list($classe, $matter, $cutting) = explode('_', $str);
            return $this->service->getListMoyenneMatter($classe, $matter, $cutting);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function frensh(string $str)
    {
        try {
            list($classe, $matter, $cutting) = explode('_', $str);
            return $this->service->moyenneFrensh($classe, $matter, $cutting);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function export(string $str)
    {
        try {
            list($classe, $matter, $cutting,) = explode('_', $str);
            $libelle = $this->service->getClasse($classe)['libelle'];
            $cutting = $this->service->getCutting($cutting)['cutting']['libelle'];
            $matters = $this->service->getMatter($matter);
            $verif = ($matters['matter']['id'] === 2 && $matters['level_id'] < 5) ? true:false;
            $value = mt_rand(100, 1000);
            $name = $libelle.'_'.str_replace(' ', '_', ucwords($cutting)).'_'.$matters['matter']['symbol'];
            return Excel::download(
                new File2Export($classe, $libelle, $matters['matter']['symbol'], $cutting, $verif), 
                'Fiche_Moyenne_'.$name.'_'.$value.'_'.$str.'.xlsx'
            );
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function import(Request $request, string $str)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls|max:5120'
            ]);
            $file = $request->file('file');
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $explod = explode('_', $name);
            if(!(($explod[7].'_'.$explod[8].'_'.$explod[9]) == $str)) {
                return back()->with([
                    'str' => 'danger',
                    'msg' => 'Une erreur, fichier incompactible !'
                ]);
            }
            $matter = $this->service->getMatter($explod[8]);
            ($matter['matter']['id'] === 2 && $matter['level_id'] < 5) ? 
            Excel::import(new FileFrenshImport($str), $file):
            Excel::import(new File3Import($str), $file);
            return to_route('moyenne.list', $str)->with([
                'str' => 'success',
                'msg' => 'Importation réussie. En attente des traitement !'
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function generate(string $str)
    {
        try {
            list($classId, $cuttingId) = explode('_', $str);
            $classe = $this->service->getClasse($classId);
            $matters = $this->service->getMatters($classId);
            $cutting = $this->service->getCutting($cuttingId);
            $matieres = $classe['level_id'] < 5
            ? array_merge($this->service->getSubMatter(), json_decode($matters, true))
            : json_decode($matters, true);
            $dts = $this->service->getMoyenneCutting(
                $classe['level_id'], $classId, $cuttingId, $classe['serie_id']
            );
            
            $name = 'liste_moyenne_'.$classe->libelle.'_'.$cutting->cutting->symbol;
            $pdf = PDF::loadView('pdf.listes.moyenne_trimestre',[
                'school' => $this->service->school(),
                'matters' => $matieres,
                'cutting' => $cutting,
                'classe' => $classe,
                'datas' => $dts,
            ])->setPaper('A3', 'landscape');

            $canvas = $pdf->getDomPDF()->getCanvas();
            $canvas->page_text(
                1160,      // x
                822,      // y
                "{PAGE_NUM} / {PAGE_COUNT}",
                null,
                9
            );
            return $pdf->stream($name.'.pdf');
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function generate_1(string $str)
    {
        try {
            list($classId, $matter, $cuttingId) = explode('_', $str);
            $matters = $this->service->getMatter($matter);
            $classe = $this->service->getClasse($classId);
            $cutting = $this->service->getCutting($cuttingId);
            $int = ($matters['matter']['id'] === 2 && $matters['level_id'] < 5) ? true:false;
            $data = $int ?  $this->service->getStudentMoyenneFrensh($classId, $matter, $cuttingId):
            $this->service->getStudentMoyenne($classId, $matter, $cuttingId);
            
            $name = 'liste_moyenne_'.$classe->libelle.'_'.$cutting->cutting->symbol.'_'.$matters->matter->symbol;
            $pdf = PDF::loadView('pdf.listes.moyenne_matiere_'.($int ? 2:1),[
                'school' => $this->service->school(),
                'matters' => $matters,
                'cutting' => $cutting,
                'classe' => $classe,
                'data' => $data,
            ]);

            $canvas = $pdf->getDomPDF()->getCanvas();
            $canvas->page_text(
                570,      // x
                822,      // y
                "{PAGE_NUM} / {PAGE_COUNT}",
                null,
                9
            );
            return $pdf->stream($name.'.pdf');
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function nonClasse(string $str)
    {
        try {
            list($classId, $cuttingId) = explode('_', $str);
            $classe = $this->service->getClasse($classId);
            $cutting = $this->service->getCutting($cuttingId);
            $dts = $this->service->moyenneTrimestreClasseStudent(
                $classId, $cuttingId
            );
            return view('pages.moyennes.edit',[
                'cutting' => $cutting,
                'classe' => $classe,
                'datas' => $dts
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function classeNon(Request $request, string $str)
    {
        try {
            $valide = $request->validate([
                'students' => 'required|array',
                'students.*' => 'required|string',
                'checked' => 'nullable|array',
                'checked*' => 'nullable|string',
                'string'  => 'required|string',
            ]);

            if(!($valide['string'] == $str) || !($request['checked'])) {
                return back()->with([
                    'str' => 'danger',
                    'msg' => 'Erreur, Incompactibilité ou aucune case cochée !'
                ]);
            }
            
            NonClasseStudentEvent::dispatch($valide['students'], $valide['checked'], $str);
            return to_route('moyenne.show', $str)->with([
                'str' => 'success',
                'msg' => 'Elève déclaré non classé. Validation effectuée, traitement en attente !'
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function export_1(string $str)
    {
        try {
            list($id1, $id2) = explode('_', $str);
            $classe = $this->service->getClasse($id1);
            $cutting = $this->service->getCutting($id2);
            $string = mt_rand(100, 1000);
            $name = $classe['libelle'].'_'.str_replace(' ', '_', ucwords($cutting['cutting']['libelle']));
            return Excel::download(
                new GlobalExport($classe, $cutting), 
                'Fiche_Moyenne_Global_'.$name.'_'.$string.'_'.$str.'.xlsx'
            );
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function import_1(Request $request, string $str)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls|max:5120'
            ]);
            $file = $request->file('file');
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $explod = explode('_', $name);
            if(!(($explod[7].'_'.$explod[8]) == $str)) {
                return back()->with([
                    'str' => 'danger',
                    'msg' => 'Une erreur, fichier incompactible !'
                ]);
            }
            Excel::import(new GlobalImport($str), $file);
            return to_route('moyenne.show', $str)->with([
                'str' => 'success',
                'msg' => 'Importation réussie. En attente des traitement !'
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }
}
