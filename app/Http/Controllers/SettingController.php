<?php

namespace App\Http\Controllers;

use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $service;
    function __construct(SettingService $service) {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('pages.school.index',[
                'school' => $this->service->school()
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
    public function indexSlot()
    {
        try {
            return view('pages.slots.index',[
                'data' => $this->service->slotTime()
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
    public function createSlot()
    {
        try {
            return view('pages.slots.create', [
                'data' => []
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
    public function storeSlot(Request $request)
    {
        try {
            $dts = $request->validate([
                'debt1' => 'required|array',
                'debt2' => 'required|array',
                'fin1' => 'required|array',
                'fin2' => 'required|array',
                'debt1.*' => 'nullable|date_format:H:i',
                'debt2.*' => 'nullable|date_format:H:i',
                'fin1.*' => 'nullable|date_format:H:i',
                'fin2.*' => 'nullable|date_format:H:i',
            ]);
            if((count($dts['debt1']) == count($dts['fin1'])) && count($dts['debt2']) == count($dts['fin2'])) {
                $this->service->storeSlot($dts['debt1'], $dts['debt2'], $dts['fin1'], $dts['fin2']);
            }
            return to_route('slot.index')->with([
                'str' => 'success',
                'msg' => 'Enrégistrement effectué.'
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            return view('pages.school.create',[
                'dren' => $this->service->getDren(),
                'data' => $this->service->school($id)
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
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'code' => 'required|string',
                'num' => 'required|string',
                'name' => 'required|string',
                'dren' => 'required|integer',
                'ville' => 'required|string',
                'address' => 'required|string|unique:schools,addres',
                'email' => 'required|email|unique:schools,email',
                'phon' => 'required|numeric|min:10|unique:schools,phon',
                'created' => 'required|date',
                'opening' => 'required|date',
            ]);

            // .... Gestion de logo .... //
            if($request['file']) {
                $request->validate([
                    'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
                ]);
                $file = $request->file('file');
                $filename = $id. '_logo_' . $file->getClientOriginalName();
                $path = $file->storeAs('logo', $filename, 'public');
            }
            $this->service->update($id, $request, $path ?? null);
            return to_route('setting.index')->with([
                'str' => 'success',
                'msg' => 'Enrégistrement effectué.'
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function editSlot(Request $request)
    {
        try {
            $data = $this->service->getSolt($request['id']);
            return $data;
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }
    }


    public function updateSlot(Request $request) {
        try {
            $dts = $request->validate([
                'id' => 'required|integer',
                'dbt' => 'required|date_format:H:i',
                'fin' => 'required|date_format:H:i',
            ]);
            $this->service->updateSol($dts['dbt'], $dts['fin'], $dts['id']);
            return to_route('slot.index')->with([
                'str' => 'info',
                'msg' => 'Mise à jour effectuée'
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
