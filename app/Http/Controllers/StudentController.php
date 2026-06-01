<?php

namespace App\Http\Controllers;

use App\Exports\File1Export;
use App\Imports\File1Import;
use App\Services\StudentService;
use App\Http\Requests\StudentRequest;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $service, $school;
    function __construct(StudentService $service) {
        $this->service = $service;
        $this->school = auth()->user()->school_id;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('pages.students.index',[
                'years' => $this->service->getYears()
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
     * Show the form for creating a new resource.
     */
    public function yajra()
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
     * Show the form for creating a new resource.
     */
    public function year(String $id) {
        try {
            return view('pages.students.list_year',[
                'year' => $this->service->getYear($id)
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
     * Show the form for creating a new resource.
     */
    public function search(String $id) {
        try {
            return $this->service->getDtYears($id);
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
    public function create()
    {
        try {
            return view('pages.students.create',[
                'data' => null
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
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $valid)
    {
        try {
            $this->service->getStore($valid);
            return to_route('student.index')->with([
                'str' => 'success',
                'msg' => 'Enrégistrement effectué.'
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            return view('pages.students.create',[
                'data' => $this->service->getStdt($id)
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
     * Update the specified resource in storage.
     */
    public function update(StudentRequest $valid, string $id)
    {
        try {
            $this->service->update($id, $valid);
            return to_route('student.index')->with([
                'str' => 'info',
                'msg' => 'Mise à jour effectuée.'
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }
    }

    public function export()
    {
        try {
            $str = mt_rand(100, 1000);
            $name = 'Fiche_Enregistrement_'.$str.'_'.$this->school;
            return Excel::download(new File1Export, $name.'.xlsx');
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
            list($lib1, $lib2, $str, $id) = explode('_', $name);
            if(!($id == $this->school)) {
                return back()->with([
                    'str' => 'danger',
                    'msg' => 'Erreur, fichier incompactible !'
                ]);
            }
            Excel::import(new File1Import, $file);
            return to_route('student.index')->with([
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
