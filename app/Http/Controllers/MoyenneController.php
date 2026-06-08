<?php

namespace App\Http\Controllers;

use App\Exports\File2Export;
use App\Imports\File3Import;
use App\Imports\FileFrenshImport;
use App\Services\MoyenneService;
use App\Events\MoyenneEditEvent;
use App\Events\MoyenneEditFrenshEvent;
use Maatwebsite\Excel\Facades\Excel;
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
            return $this->service->getYajra();
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
            return $this->service->getMoyenneMCuttingClasse($classe['level_id'], $class, $cutting);
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
