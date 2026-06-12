<?php

namespace App\Http\Controllers;

use App\Imports\TeacherImport;
use App\Services\TeacherService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    protected $service;
    function __construct(TeacherService $service) {
        $this->service = $service;
    }

    public function index()
    {
        try {
            return view('pages.teachers.index');
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
            return $this->service->getYajra('1');
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
        try {
            return view('pages.teachers.create',[
                'data' => [],
                'matters' => $this->service->getMatters()
            ]);
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
            $request->validate([
                'first' => 'required|string',
                'last' => 'required|string',
                'civility' => 'required|string',
                'date' => 'required|date',
                'lieu' => 'required|string',
                'piece' => 'required|string',
                'numero' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'phon' => 'required|numeric|unique:users,telephon',
                'etude' => 'required|string',
                'diplom' => 'required|string',
                'enseignant' => 'required|string',
                'autorisate' => 'required|string',
                'num_auto' => 'nullable|string',
                'date_acquise' => 'nullable|string',
                'matter' => 'nullable|integer',
                'experiens' => 'nullable|integer',
            ]);
            $this->service->getStore($request);
            return to_route('teacher.index')->with([
                'str' => 'success',
                'msg' => 'Enregistrement effectué !'
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }
    }

    
    public function show()
    {
        try {
            return view('pages.teachers.detail');
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    public function disabled() 
    {
        try {
            return $this->service->getYajra('0');
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
        try {
            return view('pages.teachers.create',[
                'data' => $this->service->getTeacher($id),
                'matters' => $this->service->getMatters()
            ]);
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
                'first' => 'required|string',
                'last' => 'required|string',
                'civility' => 'required|string',
                'date' => 'required|date',
                'lieu' => 'required|string',
                'piece' => 'required|string',
                'numero' => 'required|string',
                'email' => 'required|email',
                'phon' => 'required|numeric',
                'etude' => 'required|string',
                'diplom' => 'required|string',
                'enseignant' => 'required|string',
                'autorisate' => 'required|string',
                'num_auto' => 'nullable|string',
                'date_acquise' => 'nullable|string',
                'matter' => 'nullable|integer',
                'experiens' => 'nullable|integer',
            ]);
            $this->service->getUpdate($id, $request);
            return to_route('teacher.index')->with([
                'str' => 'success',
                'msg' => 'Mise à jour effectuée !'
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    public function export()
    {
        try {
            $str = mt_rand(100, 999).'_'.$this->service->export();
            $path = storage_path('app/download/Excel/fiche_enseignant.xlsx');
            $name = 'Fiche_Enseignant_'.$str;
            return response()->download($path, $name.'.xlsx');
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    public function import(Request $request) 
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls|max:5120'
            ]);
            $file = $request->file('file');
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $str = explode('_', $name);
            if(!($str[3] == $this->service->export()) || !($str[1] === 'Enseignant')) {
                return back()->with([
                    'str' => 'danger',
                    'msg' => 'Erreur, fichier incompactible !'
                ]);
            }
            Excel::import(new TeacherImport, $file);
            return to_route('teacher.index')->with([
                'str' => 'success',
                'msg' => 'Importation réussie.'
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function destroy(string $id)
    {
        try {
            $this->service->destroy($id);
            return to_route('teacher.index')->with([
                'str' => 'success',
                'msg' => 'Enseignant Supprimé !'
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
