<?php

namespace App\Http\Controllers;

use App\Imports\File2Import;
use App\Services\ClasseService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ClasseController extends Controller
{
    protected $service;
    function __construct(ClasseService $service) {
        $this->service = $service;
    }

    
    public function index()
    {
        try {
            return view('pages.classes.index',[
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

    
    public function list(string $id)
    {
        try {
            return view('pages.classes.show',[
                'classe' => $this->service->classe($id),
                'days' => $this->service->getDayWeek(),
                'users' => $this->service->getTeacherClasse($id)
            ]);
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
            $classe = $this->service->classe($id);
            $nbre = $classe['lv2'] != 'mix' ? 1:2;
            $str = mt_rand(100, 999);
            $path = storage_path('app/download/Excel/register_'.$nbre.'.xlsx');
            $str = $str.'_'.$classe['school_id'].$classe['school_year_id'];
            $name = 'Fiche_Inscription_'.$classe['libelle'].'_'.$str.'_'.$id;
            return response()->download($path, $name.'.xlsx');
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function import(Request $request) {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls|max:5120'
            ]);
            $file = $request->file('file');
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $explod = explode('_', $name);
            $classe = $this->service->classe($explod[5]);
            if($classe && !($classe['school_id'].$classe['school_year_id'] == $explod[4])) {
                return back()->with([
                    'str' => 'danger',
                    'msg' => 'Erreur, fichier incompactible !'
                ]);
            }
            if(!($classe['invalid'] || $classe['status'])) {
                return back()->with([
                    'str' => 'danger',
                    'msg' => 'Erreur, problème lié à cette classe !'
                ]);
            }
            $lv2 = $classe['lv2'] == 'mix' ? true:false;
            Excel::import(new File2Import($explod[5], $lv2), $file);
            return back()->with([
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


    public function dataTable(string $id) {
        try {
            return $this->service->dataTable($id);
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
            $dts = $request->validate([
                'number' => 'required|integer',
                'nbre' => 'required|integer',
                'serie' => 'nullable|integer',
                'lv2' => 'nullable|string',
            ]);
            $invalid = (in_array($id, [5, 6, 7]) && empty($request['serie']));
            if($invalid) {
                return back()->with([
                    'str' => 'danger',
                    'msg' => 'Une erreur est survenue !'
                ]);
            }
            $this->service->getStore($id, $dts['number'], $dts['nbre'], $request['lv2'], $request['serie']);
            return to_route('classe.show', $id)->with([
                'str' => 'success',
                'msg' => 'Enrégistrement effectué.'
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
            $series = in_array($id, [5, 6, 7]) ? 
            $this->service->getSerie($level['symbol']):null;
            return view('pages.classes.detail',[
                'level' => $level,
                'series' => $series,
                'data' => $this->service->getClass($id)
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function timeList(string $str)
    {
        try {
            $classe = $this->service->classe($str);
            $matters = $this->service->getMatters($classe['level_id'], $classe['serie_id']);
            return view('pages.classes.times',[
                'classe' => $classe,
                'matters' => $matters,
                'times' => $this->service->getTime(),
                'days' => $this->service->getDayWeek(),
                'data' => $this->service->getTableTime($str)
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function create(string $str)
    {
        try {
            $classe = $this->service->classe($str);
            $matters = $this->service->getMatters($classe['level_id'], $classe['serie_id']);
            return view('pages.classes.create',[
                'classe' => $classe,
                'matters' => $matters,
                'times' => $this->service->getTime(),
                'days' => $this->service->getDayWeek(),
                'data' => $this->service->getTableTime($str)
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function addTime(Request $request, string $str)
    {
        try {
            $request['select'] = array_filter($request['select'], function ($value) {
                return !blank($value);
            });
            $data = $request->validate([
                'select' => 'required|array',
                'select.*' => 'required|string'
            ]);

            $this->service->storeTime($data['select'], $str);
            return to_route('classe.time', $str)->with([
                'str' => 'success',
                'msg' => 'Enregistrment effectué !'
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function teacher(string $str)
    {
        try {
            $classe = $this->service->classe($str);
            $matters = $this->service->getMatters($classe['level_id'], $classe['serie_id']);
            return view('pages.classes.edit',[
                'classe' => $classe,
                'matters' => $matters,
                'users' => $this->service->getTeachers($str)
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function teaches(Request $request, string $str)
    {
        try {
            $valide = $request->validate([
                'matter' => 'required|array',
                'matter.*' => 'required|integer',
                'teacher' => 'required|array',
                'teacher.*' => 'nullable|string',
                'cheched' => 'required|integer',
            ]);

            if (!collect($valide['teacher'])->filter()->isNotEmpty()) {
                return back()->with([
                    'str' => 'danger',
                    'msg' => 'Aucun enseignant selectionné !'
                ]);
            }

            $this->service->teachesStore(
                $valide['matter'], $valide['teacher'], $valide['cheched'], $str
            );
            return to_route('classe.list', $str)->with([
                'str' => 'success',
                'msg' => 'Enseignant ajouté !'
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
            $data = $this->service->classe($request['id']);
            return $data ?
            [
                'lib' => $data['libelle'],
                'eff' => $data['effectif'],
                'status' => $data['status'],
                'lv2' => $data['lv2'],
                'serie' => $data['serie'] ?$data['serie']['libelle']:null,
            ] :
            null;
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
                'id' => 'required|integer',
                'number' => 'required|integer',
            ]);
            $this->service->update($request);
            return to_route('classe.show', $id)->with([
                'str' => 'success',
                'msg' => 'Mise à jour effectuée.'
            ]);
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
            $classe = $this->service->classe($str);
            $data = $this->service->getStudentClasse($str);
            $pdf = Pdf::loadView('pdf.listes.classe',[
                'data' => $data,
                'classe' => $classe,
                'school' => $this->service->school(),
            ]);

            $canvas = $pdf->getDomPDF()->getCanvas();
            $canvas->page_text(
                570,      // x
                822,      // y
                "{PAGE_NUM} / {PAGE_COUNT}",
                null,
                9
            );
            return $pdf->stream('liste_de_classe_'.$classe['libelle'].'.pdf');
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function generatePdf(string $str)
    {
        try {
            $classe = $this->service->classe($str);
            $data = $this->service->getTableTime($str);
            $pdf = Pdf::loadView('pdf.listes.emploi_temps',[
                'data' => $data,
                'classe' => $classe,
                'times' => $this->service->getTime(),
                'days' => $this->service->getDayWeek(),
                'school' => $this->service->school(),
            ])
            ->setPaper('a4', 'landscape');
            return $pdf->stream('emploi_temps_'.$classe['libelle'].'.pdf');
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }
    

    public function destroy(Request $request, string $id)
    {
        try {
            $dt = $request->validate([
                'id' => 'required|integer'
            ]);
            $reust = $this->service->delete($dt['id']);
            return to_route('classe.show', $id)->with([
                'str' => $reust ? 'success':'danger',
                'msg' => $reust ? 'Suppression effectuée.':'Action non achévée, classe active !'
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
