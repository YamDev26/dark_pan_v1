<?php

namespace App\Http\Controllers;

use App\Services\RegisterService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    protected $service;
    function __construct(RegisterService $service) {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('pages.registers.index',[
                'levels' => $this->service->getLevels()
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    public function yajra_1() {
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
     * Show the form for creating a new resource.
     */
    public function create(Request $query)
    {
        try {
            $dts = $this->service->search($query['info']);
            return $dts ? [
                'name' => strtoupper($dts['student']->first).' '.ucwords($dts['student']->last),
                'genre' => $dts['student']->genre,
                'date' => date('d/m/Y', strtotime($dts['student']->date)),
                'lieu' => ucwords($dts['student']->lieu),
                'matricul' => $dts['student']->matricul,
                'id' => $dts['student']->id,
                'classe' => $dts['class'],
            ]:null;
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    public function getClasse(Request $dts)
    {
        try {
            return $this->service->getClasse($dts['level'], $dts['serie'], $dts['lv2']);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return view('pages.registers.detail',[
                'level' => $this->service->level($id)
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    public function yajra_2(string $id)
    {
        try {
            return $this->service->getSearch($id);
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
    public function search(Request $request)
    {
        try {
            $dt = $this->service->getRegister($request['id']);
            return $dt ? [
                'name' => strtoupper($dt->school_student->student->first).' '.ucwords($dt->school_student->student->last),
                'matricul' => $dt->school_student->student->matricul,
                'genre' => $dt->school_student->student->genre,
                'date' => date('d/m/Y', strtotime($dt->school_student->student->date)),
                'lieu' => ucwords($dt->school_student->student->lieu),
                'residence' => ucwords($dt->school_student->residence),
                'affect' => $dt->affecte ? 'Oui':'Non',
                'redoubant' => $dt->redoubant ? 'Oui':'Non',
                'boursier' => $dt->boursier ? 'Oui':'Non',
                'classe' => $dt->get_classe->libelle,
                'level' => $dt->get_classe->level->symbol,
                'serie' => $dt->get_classe->serie_id ? $dt->get_classe->serie->libelle:null,
                'lv2' => $dt->lv2 ?? $dt->get_classe->lv2,
                'inscrit' => date('d/m/Y', strtotime($dt->created_at))
            ]:null;
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $dts = $this->service->destroy($request['id']);
            return back()->with([
                'str' => $dts ? 'success':'danger',
                'msg' => $dts ? 'Suppression effectuée.':'Une erreur est survenue !'
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
