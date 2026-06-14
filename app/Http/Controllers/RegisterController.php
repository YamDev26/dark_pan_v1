<?php

namespace App\Http\Controllers;

use App\Services\RegisterService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class RegisterController extends Controller
{
    protected $service;
    function __construct(RegisterService $service) {
        $this->service = $service;
    }

    
    public function index()
    {
        try {
            return view('pages.registers.index',[
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


    public function dataTable() {
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

    
    public function create(Request $query)
    {
        try {
            $dts = $this->service->search($query['info']);
            return $dts ? [
                'name' => strtoupper($dts['student']->first).' '.ucwords($dts['student']->last),
                'genre' => $dts['student']->genre,
                'date' => date('d/m/Y', strtotime($dts['student']->date)),
                'lieu' => ucwords($dts['student']->lieu),
                'matricul' => $dts['student']->matricul,
                'id' => $dts['student']->id,
                'classe' => $dts['class'],
            ]:null;
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function getClasse(Request $dts)
    {
        try {
            return $this->service->getClasse($dts['level'], $dts['lv2'], $dts['serie']);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function getSerie(Request $dts) 
    {
        try {
            return $this->service->getSerie($dts['level']);
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
                'student'    => 'required|exists:school_students,id',
                'matricule'  => 'required|exists:students,matricul',
                'level'      => 'required|exists:levels,id',
                'classe'     => 'required|exists:get_classes,id',
                'affecte'    => 'required|string',
                'redoublant' => 'required|string',
                'boursier'   => 'required|string',
                'interne'    => 'required|string',
            ]);

            $exist = $this->service->search($request['matricule']);
            if(!$exist['class'] && !($exist['student']->id == $request['student'])) {
                return back()->with([
                    'str' => 'danger',
                    'msg' => 'Erreur d\'incompatibilité !'
                ]);
            }

            $this->service->getStore(
                $request['student'], $request['classe'], $request['affecte'], $request['redoublant'], 
                $request['boursier'], $request['interne'], $request['lv2'] ?? null
            );
            return to_route('register.index')->with([
                'str' => 'success',
                'msg' => 'Inscription effectée'
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
            return view('pages.registers.detail',[
                'level' => $this->service->level($id)
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function data(string $id)
    {
        try {
            return $this->service->getSearch($id);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    
    public function search(Request $request)
    {
        try {
            $dt = $this->service->getRegister($request['id']);
            return $dt ? [
                'name' => strtoupper($dt->school_student->student->first).' '.ucwords($dt->school_student->student->last),
                'matricul' => $dt->school_student->student->matricul,
                'genre' => $dt->school_student->student->genre,
                'date' => date('d/m/Y', strtotime($dt->school_student->student->date)),
                'lieu' => ucwords($dt->school_student->student->lieu),
                'residence' => ucwords($dt->school_student->residence),
                'affect' => $dt->affecte ? 'Oui':'Non',
                'redoubant' => $dt->redoubant ? 'Oui':'Non',
                'boursier' => $dt->boursier ? 'Oui':'Non',
                'classe' => $dt->get_classe->libelle,
                'level' => $dt->get_classe->level->symbol,
                'serie' => $dt->get_classe->serie_id ? $dt->get_classe->serie->libelle:null,
                'lv2' => $dt->lv2 ?? $dt->get_classe->lv2,
                'inscrit' => date('d/m/Y', strtotime($dt->created_at)),
                'id' => $dt->school_student->id
            ]:null;
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function generate(string $str)
    {
        try {
            $data = $this->service->getRegister($str);
            $school = $this->service->school();
            $writer = new PngWriter();
            $qrCode = new QrCode(
                data:'Code : '. $school->code,
                size: 120,
            );
            $result = $writer->write($qrCode);
            $image = 'data:image/png;base64,'.base64_encode($result->getString());

            $pdf = PDF::loadView('pdf.file_1',[
                'data' => $data,
                'qrcode' => $image,
                'school' => $school
            ])->setPaper('A4', 'portrait');
            return $pdf->stream('Fiche_Inscription_'.mt_rand(100, 1000).'.pdf');
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function destroy(Request $request)
    {
        try {
            $dts = $this->service->destroy($request['id']);
            return back()->with([
                'str' => $dts ? 'success':'danger',
                'msg' => $dts ? 'Suppression effectuée.':'Une erreur est survenue !'
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
