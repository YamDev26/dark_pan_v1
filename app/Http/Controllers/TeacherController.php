<?php

namespace App\Http\Controllers;

use App\Services\TeacherService;
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
            return $this->service->getYajra();
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
            $data = $request->validate([
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
