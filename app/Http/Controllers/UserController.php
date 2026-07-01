<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            return view('pages.users.index');
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function dataTable()
    {
        try {
            return $this->service->dataTable('1');
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
            return view('pages.users.create',[
                'data' => [],
                'roles' => $this->service->getRole(),
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

    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'role' => 'required|exists:roles,id',
                'civility' => 'required|string',
                'first' => 'required|string',
                'last' => 'required|string',
                'date' => 'required|date',
                'lieu' => 'required|string',
                'piece' => 'required|string',
                'numero' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'phon' => 'required|numeric|unique:users,telephon'
            ]);

            $this->service->getStoreUser($request);
            return to_route('user.index')->with([
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
