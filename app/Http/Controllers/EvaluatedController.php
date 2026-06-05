<?php

namespace App\Http\Controllers;

use App\Services\EvaluatedService;
use Illuminate\Http\Request;

class EvaluatedController extends Controller
{
    protected $service;
    function __construct(EvaluatedService $service) {
        $this->service = $service;
    }

    public function index()
    {
        try {
            return view('pages.evaluated.index');
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }
    

    public function yajra() {
        try {
            return $this->service->getClasse();;
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function matter(Request $query) {
        try {
            $class = $this->service->classe($query['id']);
            $matters = $this->service->getMatters($class['level_id'], $class['serie_id']);
            return ['classe' => $class, 'matters' => $matters];
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function store(Request $request)
    {
        try {
            $add = $request->validate([
                'cutting' => 'required|exists:cutting_school_years,id',
                'matter' => 'required|exists:level_matters,id',
                'classe' => 'required|exists:get_classes,id',
                'type' => 'required|exists:evaluated_types,id',
                'value' => 'required|integer',
                'date' => 'required|date|before_or_equal:today',
                'sub' => 'nullable|integer',
            ]);

            $evaluat = $this->service->getStore($request);
            return to_route('note.create', $evaluat)->with([
                'str' => 'success',
                'msg' => 'Evaluation crée, Ajouter les notes !'
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
            list($class, $matter) = explode('_', $str);
            $mat = $this->service->matter($matter);
            $subMatter = ($mat->matter->id == 2 && $mat->level_idl < 5) ? 
            $this->service->subMatters():null;
            return view('pages.evaluated.detail',[
                'matter' => $mat,
                'value' => [10, 20, 40],
                'matters' => $subMatter,
                'getType' => $this->service->getType(),
                'classe' => $this->service->classe($class),
                'data' => $this->service->getEvaluated($matter, $class),
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function edit(Request $request)
    {
        try {
            $data = $this->service->evaluated($request['id']);
            return $data;
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'evaluat' => 'required|exists:evaluateds,id',
                'type' => 'required|exists:evaluated_types,id',
                'note' => 'required|integer',
                'date' => 'required|date|before_or_equal:today',
                'subE' => 'nullable|integer',
                'status' => 'nullable|string',
            ]);
            list($id, $str) = explode('-',$id);
            if(!($request['evaluat'] == $id)) {
                return to_route('evaluated.show', $str)->with([
                    'str' => 'danger',
                    'msg' => 'Une erreur est survenue !'
                ]);
            }
            $result = $this->service->update($request);
            return to_route('evaluated.show', $str)->with([
                'str' => $result ? 'success':'danger',
                'msg' => $result ? 'Mise à jour éffectuée !':'Impossible de supprimer !'
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'.$e->getMessage()
            ]);
        }
    }

    
    public function destroy(Request $request, string $id)
    {
        try {
            $val = $request->validate([
                'id' => 'required|exists:evaluateds,id'
            ]);
            list($id, $str) = explode('-',$id);
            if(!($val['id'] == $id)) {
                return to_route('evaluated.show', $str)->with([
                    'str' => 'danger',
                    'msg' => 'Une erreur est survenue !'
                ]);
            }
            $result = $this->service->destroy($val['id']);
            return to_route('evaluated.show', $str)->with([
                'str' => $result ? 'success':'danger',
                'msg' => $result ? 'Evaluation supprimée':'Impossible de supprimer !'
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
