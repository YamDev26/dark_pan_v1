<?php

namespace App\Http\Controllers;

use App\Services\SuperAdminService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    protected $service;
    function __construct(SuperAdminService $service) {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function indexYear() {
        try {
            return view('pages.super_admin.year');
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
    public function createYear(Request $request) {
        try {
            $table = explode('/', $request['year']);
            if(count($table) == 1){
                $table = explode('-', $request['year']); 
            }
            $request['year'] = $table[0].'-'.$table[1];
            $request['current'] = (string)Carbon::now()->year;
            $request->validate([
                'year' => 'required|string|unique:school_years,libelle',
                'current' => 'required|string|unique:school_years,created',
                'radio' => 'required|integer',
            ]);
            $this->service->createYear($request);
            return back()->with([
                'str' => 'success',
                'msg' => 'Enregistrment effectué.'
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
    public function editYear(Request $request, $str)
    {
        try {
            $table = explode('/', $request['year']);
            if(count($table) == 1){
                $table = explode('-', $request['year']); 
            }
            $request['year'] = $table[0].'-'.$table[1];
            $request->validate([
                'year' => 'required|string',
                'radio' => 'required|integer',
            ]);
            $this->service->editYear($request, $str);
            return back()->with([
                'str' => 'success',
                'msg' => 'Mis à jour effectué.'
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
    public function indexCutting()
    {
        try {
            $table = $this->service->dataYear();
            return view('pages.super_admin.cutting',[
                'year' => $table[0],
                'edit' => $table[1]
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
    public function createCutting(Request $request)
    {
        try {
            $dts = $request->validate([
                'str' => 'required|array',
                'str.*' => 'required|integer',
                'dbt' => 'required|array',
                'dbt.*' => 'required|date',
                'fin' => 'required|array',
                'fin.*' => 'required|date',
                'year'  => 'required|integer'
            ]);
            $this->service->createCtg($dts);
            return back()->with([
                'str' => 'success',
                'msg' => 'Enregistrment effectué.'
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
    public function editCuuting(Request $request)
    {
        try {
            $dts = $request->validate([
                'str' => 'required|array',
                'str/*' => 'required|integer',
                'dbt' => 'required|array',
                'dbt/*' => 'required|date',
                'fin' => 'required|array',
                'fin/*' => 'required|date',
            ]);
            $this->service->updateCutting($dts);
            return back()->with([
                'str' => 'success',
                'msg' => 'Mis à jour effectué.'
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
    public function country()
    {
        try {
            return view('pages.super_admin.nationality');
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function editCounty(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer',
                'libelle' => 'required|string',
            ]);
            $this->service->editCountry($request);
            return back()->with([
                'str' => 'success',
                'msg' => 'Mis à jour effectué.'
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function index() {
        try {
            return view('pages.super_admin.school.index',[
                'data' => $this->service->index(),
                'school' => auth()->user()->school_id
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    public function created() {
        try {
            return view('pages.super_admin.school.create',[
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


    public function stored(Request $request) {
        try {
            $data = $request->validate([
                'first' => 'required|string',
                'last' => 'required|string',
                'gender' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'phon' => 'required|numeric|unique:users,telephon',
                'code' => 'required|string|unique:schools,code',
                'num' => 'required|string|unique:schools,autorisation',
                'name' => 'required|string|unique:schools,name',
            ]);
            $this->service->stored($data);
            return to_route('school.index')->with([
                'str' => 'success',
                'msg' => 'Enrégistrement effectué.'
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }
    }

    public function edited($str) {
        try {
            return view('pages.super_admin.school.create',[
                'data' => $this->service->update($str)
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function update(Request $request, $str) {
        try {
            $request->validate([
                'code' => 'required|string',
                'num' => 'required|string',
                'name' => 'required|string',
            ]);
            $this->service->updated($request, $str);
            return to_route('school.index')->with([
                'str' => 'success',
                'msg' => 'Mise à jour effectué.'
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }
    }

    public function dren() {
        try {
            return view('pages.super_admin.drena', [
                'data' => $this->service->getDren()
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
