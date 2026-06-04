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

    
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        try {
            $valid = $request->validate([
                'cutting' => 'required|exists:cutting_school_years,id',
                'matter' => 'required|exists:level_matters,id',
                'classe' => 'required|exists:get_classes,id',
                'type' => 'required|exists:evaluated_types,id',
                'value' => 'required|integer',
                'date' => 'required|date|before_or_equal:today',
            ]);

            $this->service->getStore($valid);
            return back()->with([
                'str' => 'success',
                'msg' => 'Evaluation crée !'
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
            return view('pages.evaluated.detail',[
                'getType' => $this->service->getType(),
                'classe' => $this->service->classe($class),
                'matter' => $this->service->matter($matter),
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
        //
    }

    
    public function destroy(string $id)
    {
        //
    }
}
