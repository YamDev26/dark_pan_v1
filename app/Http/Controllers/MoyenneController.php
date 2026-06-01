<?php

namespace App\Http\Controllers;

use App\Exports\File2Export;
use App\Imports\File3Import;
use App\Services\MoyenneService;
use App\Events\MoyenneMatterStoreEvent;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class MoyenneController extends Controller
{
    protected $service;
    function __construct(MoyenneService $service) {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     */
    public function yajra_1()
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $str)
    {
        try { // dd($request);
            $validate = $request->validate([
                'str' => 'required|array',
                'str.*' => 'required|string',
                'moyen' => 'required|array',
                'moyen.*' => 'nullable|numeric'
            ]);
            MoyenneMatterStoreEvent::dispatch($validate['str'], $validate['moyen'], $str);
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            list($classe, $cutting) = explode('_', $id);

            // dd($this->service->getResultat($classe, $cutting));

            return view('pages.moyennes.detail',[
                'classe' => $this->service->getClasse($classe),
                'cutting' => $this->service->getCutting($cutting),
                'matters' => $this->service->getMatters($classe),
                'matieres' => $this->service->matieres($classe),
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'.$e->getMessage()
            ]);
        }
    }

    public function resultatTble(string $str)
    {
        try {
            list($classe, $cutting) = explode('_', $str);
            return $this->service->getResultat($classe, $cutting);
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
            list($matter, $cutting, $classe) = explode('_', $str);
            return view('pages.moyennes.moyenne_matter',[
                'classe' => $this->service->getClasse($classe),
                'cutting' => $this->service->getCutting($cutting),
                'matter' => $this->service->getMatter($matter),
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    public function yajra_2(string $str) 
    {
        try {
            list($matter, $cutting, $classe) = explode('_', $str);
            return $this->service->getYajra_2($classe, $cutting, $matter);
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
            list($matter, $cutting, $classe) = explode('_', $str);
            return view('pages.moyennes.create',[
                'classe' => $this->service->getClasse($classe),
                'cutting' => $this->service->getCutting($cutting),
                'matter' => $this->service->getMatter($matter),
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    public function yajra_3(string $str)
    {
        try {
            list($matter, $cutting, $classe) = explode('_', $str);
            return $this->service->getYajra_3($classe, $cutting, $matter);
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
            list($matter, $cutting, $classe) = explode('_', $str);
            $libelle = $this->service->getClasse($classe)['libelle'];
            $cutting = $this->service->getCutting($cutting)['cutting']['libelle'];
            $matter = $this->service->getMatter($matter)['matter']['symbol'];
            $value = mt_rand(100, 1000);
            $name = $libelle.'_'.str_replace(' ', '_', ucwords($cutting)).'_'.$matter;
            return Excel::download(
                new File2Export($classe, $libelle, $matter, $cutting), 
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
                    'msg' => 'Une erreur, vous vous êtes trompé de fichier !'
                ]);
            }
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
