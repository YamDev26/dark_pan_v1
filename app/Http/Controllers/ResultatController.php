<?php

namespace App\Http\Controllers;

use App\Services\ResultatService;
use Illuminate\Http\Request;

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



// Exemple de tableau de bord d'une classe
// Effectif : 48 élèves.
// Garçons : 26 (54,2 %).
// Filles : 22 (45,8 %).
// Moyenne générale : 11,84/20.
// Taux de réussite : 72,9 %.
// Premier : 17,35/20.
// Dernier : 06,20/20.
// Taux d'absentéisme : 3,8 %.
// Matière la plus réussie : Mathématiques.
// Matière la plus difficile : Physique.

// Si vous développez une application de gestion scolaire, 
// ces indicateurs constituent généralement le noyau d'un tableau de bord destiné au directeur, 
// au surveillant ou au professeur principal.
