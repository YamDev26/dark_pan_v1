<?php

namespace App\Http\Controllers;

use App\Services\EnseigmntMoyenneService;
use App\Exports\File2Export;
use App\Imports\File3Import;
use App\Imports\FileFrenshImport;
use App\Events\MoyenneEditEvent;
use App\Events\MoyenneEditFrenshEvent;
use App\Events\MoyenneEditDrivingEvent;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class EnseigmntMoyenneController extends Controller
{
    private $service;

    function __construct(EnseigmntMoyenneService $service) {
        $this->service = $service;
    }

    
    public function index()
    {
        try {
            return view('pages.enseignant.moyenne.index');
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
            return $this->service->getClasseUsers();
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
            $closed = $this->service->getCloseCutting($cutting);
            if(!$this->service->verifyClasse($classe, $matter) || $closed) {
                return to_route('moyennes.index')->with([
                    'str' => 'danger',
                    'msg' => 'Erreur, données incorrestes !'
                ]);
            }

            $data = $this->verifyMatter($classe, $matter, $cutting);
            return view('pages.enseignant.moyenne.create_'.$data['number'],[
                'datas' => $data['data'],
                'classe' => $this->service->getClasse($classe),
                'matter' => $this->service->getMatter($matter),
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
                'matter'  => 'required|string',
            ]);

            if((!$valide['string'] == $str)) {
                return back()->with([
                    'str' => 'danger',
                    'msg' => 'Erreur, Incompactibilité entres les informations !'
                ]);
            }
            $this->getEvent($valide, $str);
            return to_route('moyennes.show', $str)->with([
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
            list($classe, $matter, $cutting) = explode('_', $str);

            if(!$this->service->verifyClasse($classe, $matter)) {
                return to_route('moyennes.index')->with([
                    'str' => 'danger',
                    'msg' => 'Erreur, données incorrestes !'
                ]);
            }

            $matters = $this->service->getMatter($matter);
            $verif = ($matters['matter']['id'] === 2 && $matters['level_id'] < 5) ? true:false;
            return view('pages.enseignant.moyenne.show_'.($verif ? 2:1),[
                'matter' => $matters,
                'classe' => $this->service->getClasse($classe),
                'cutting' => $this->service->getCutting($cutting),
                'close' => $this->service->getCloseCutting($cutting)
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    public function table1Moyens(string $str) 
    {
        try {
            list($classe, $matter, $cutting) = explode('_', $str);
            return $this->service->getMoyenneMatters($classe, $matter, $cutting);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function table2Moyens(string $str) 
    {
        try {
            list($classe, $matter, $cutting) = explode('_', $str);
            return $this->service->getMoyenneFrenshs($classe, $matter, $cutting);
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
            return to_route('moyennes.show', $str)->with([
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
    

    private function verifyMatter($classe, $matter, $cutting)
    {
        $matters = $this->service->getMatter($matter);
        if(($matters['matter']['id'] === 2) && ($matters['level_id'] < 5)) {
            $number = 2;
            $data = $this->service->getMoyennefresh($classe, $matter, $cutting);
        }
        elseif(($matters['matter']['id'] === 13) && ($matters['matter']['libelle'] == 'Conduite')) {
            $number = 3;
            $data = $this->service->getCoduiteMoyenne($classe, $matter, $cutting);
        }
        else {
            $number = 1;
            $data = $this->service->getStudentMoyenneMatter($classe, $matter, $cutting);
        }
        return [
            'number' => $number,
            'data' => $data
        ];
    }


    private function getEvent($validate, $str)
    {
        $event = match ($validate['matter']) {
            'french'  => MoyenneEditFrenshEvent::class,
            'driving' => MoyenneEditDrivingEvent::class,
            'autres'  => MoyenneEditEvent::class,
        };

        if ($validate['matter'] === 'autres') {
            $event::dispatch(
                $validate['students'],
                $validate['moyen1'],
                $str
            );
        } 
        else {
            $event::dispatch(
                $validate['students'],
                $validate['moyen1'],
                $validate['moyen2'],
                $validate['moyen3'],
                $str
            );
        }
    }
}
