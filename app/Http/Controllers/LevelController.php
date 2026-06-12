<?php

namespace App\Http\Controllers;

use App\Services\LevelService;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    protected $service;
    function __construct(LevelService $service) {
        $this->service = $service;
    }
    

    public function index()
    {
        try {
            return view('pages::levels.index',[
                'data' => $this->service->getLevels() 
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function create(Request $val, string $id)
    {
        try {
            return view('pages.levels.create',[
                'level' => $this->service->level($id),
                'data' => $this->service->getMatters(),
                'serie' => $val['serie'] ? 
                $this->service->serie($val['serie']):false
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
            $valid = $request->validate([
                'matter' => 'required|array',
                'matter.*' => 'required|integer',
                'nbres' => 'required|array',
                'nbres.*' => 'required|integer',
            ]);

            if(!(sizeof($valid['matter']) == sizeof($valid['nbres']))) {
                return back()->with([
                    'str' => 'danger',
                    'msg' => 'Une erreur, incohérence dans la saisié !'
                ]);
            }
            $valid['matter'][] = '13'; $valid['nbres'][] = '1';
            $this->service->getStore($str, $valid['matter'], $valid['nbres']);
            $str = explode('_', $str);
            return to_route('level.show', $str[0])->with([
                'serie' => sizeof($str) > 1 ? $str[1]:null,
                'str' => 'success',
                'msg' => 'Coefficient ajouté !'
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function show(string $id)
    {
        try {
            $level = $this->service->level($id);
            $data = $level->cycle2 ? 
            $this->service->getDataCycle2($level['id'], $level['symbol']):
            $this->service->getDataCycle1($id);
            return view($level->cycle2 ? 'pages.levels.detail_2':'pages.levels.detail',[
                'level' => $level,
                'data' => $data
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function edit(string $id)
    {
        //
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
