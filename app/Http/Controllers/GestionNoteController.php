<?php

namespace App\Http\Controllers;

use App\Services\GestionNoteService;
use Illuminate\Http\Request;

class GestionNoteController extends Controller
{
    protected $service;
    function __construct(GestionNoteService $service) {
        $this->service = $service;
    }
    
    public function index()
    {
        //
    }

    
    public function create(string $id)
    {
        try {
            $evaluat = $this->service->evaluated($id);
            $dtas = $this->service->getStudent($evaluat['get_classe_id']);
            return view('pages.notes.create',[
                'evaluat' => $evaluat,
                'datas' => $dtas,
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function store(Request $request, string $id)
    {
        try {
            $valid = $request->validate([
                'str' => 'required|array',
                'str.*' => 'required|integer',
                'note' => 'required|array',
                'note.*' => 'nullable|integer',
                'evaluat' => 'required|exists:evaluateds,id',
            ]);

            dd($valid);
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
            return view('pages.notes.detail',[
                'evaluat' => $this->service->evaluated($id)
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function listNot(string $id) 
    {
        try {
            return $this->service->getNote($id);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    public function export(string $id) {
        try {
            $evaluat = $this->service->evaluated($id);
            dd($evaluat);
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
