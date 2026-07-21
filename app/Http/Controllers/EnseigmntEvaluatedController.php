<?php

namespace App\Http\Controllers;

use App\Services\EnseigmntEvaluatedService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NoteExport;
use App\Imports\NoteImport;
use App\Events\EvaluatNotEvant;
use Illuminate\Http\Request;

class EnseigmntEvaluatedController extends Controller
{
    private $service;

    public function __construct(EnseigmntEvaluatedService $service)
    {
        $this->service = $service;
    }
    
    public function index()
    {
        try {
            return view('pages.enseignant.evaluated.index');
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
            return $this->service->getClasseUsers();
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
            $evaluat = $this->service->evaluated($str);
            $existe = $this->service->verifyClasse(
                $evaluat->get_classe_id,
                $evaluat->level_matter_id
            );
            if(!$existe) {
                return to_route('evaluation.index')->with([
                    'str' => 'danger',
                    'msg' => 'Erreur, données incorrestes !'
                ]);
            }
            return view('pages.enseignant.evaluated.create',[
                'evaluat' => $evaluat,
                'datas' => $this->service->getStudent($evaluat['get_classe_id']),
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
                'cutting' => 'required|exists:cutting_school_years,id',
                'matter' => 'required|exists:level_matters,id',
                'classe' => 'required|exists:get_classes,id',
                'type' => 'required|exists:evaluated_types,id',
                'value' => 'required|integer',
                'date' => 'required|date|before_or_equal:today',
                'sub' => 'nullable|integer',
            ]);

            $evaluat = $this->service->getStore($request);
            return to_route('evaluation.create', $evaluat)->with([
                'str' => 'success',
                'msg' => 'Evaluation enregistrée, Ajout des notes !'
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function show(string $str)
    {
        try {
            list($class, $matter) = explode('_', $str);

            if(!$this->service->verifyClasse($class, $matter)) {
                return to_route('evaluation.index')->with([
                    'str' => 'danger',
                    'msg' => 'Erreur, données incorrestes !'
                ]);
            }

            $mat = $this->service->matter($matter);
            $subMatter = ($mat->matter->id == 2 && $mat->level_id < 5) ? 
            $this->service->subMatters():null;
            return view('pages.enseignant.evaluated.detail',[
                'matter' => $mat,
                'value' => [10, 20, 40],
                'matters' => $subMatter,
                'getType' => $this->service->getType(),
                'classe' => $this->service->classe($class),
                'data' => $this->service->getEvaluated($matter, $class),
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
            $evaluatet = $this->service->evaluated($id);
            $existe = $this->service->verifyClasse(
                $evaluatet->get_classe_id,
                $evaluatet->level_matter_id
            );
            if(!$existe) {
                return to_route('evaluation.index')->with([
                    'str' => 'danger',
                    'msg' => 'Erreur, données incorrestes !'
                ]);
            }
            return view('pages.enseignant.evaluated.show',[
                'evaluat' => $evaluatet,
                'existe' => $this->service->existNote($id)
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function dataTableNote(string $str) 
    {
        try {
            list($classe, $evaluation) = explode('_', $str);
            return $this->service->getNoteEvaluated($classe, $evaluation);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function addNot(Request $request, string $str)
    {
        try {
            $valid = $request->validate([
                'str' => 'required|array',
                'str.*' => 'required|integer',
                'note' => 'required|array',
                'note.*' => 'nullable|string',
                'evaluat' => 'required|exists:evaluateds,id',
            ]);

            if(!($valid['evaluat'] == $str)) {
                return back()->with([
                    'str' => 'danger',
                    'msg' => 'Une erreur est survenue !'
                ]);
            }
            
            EvaluatNotEvant::dispatch($valid['str'], $valid['note'], $str);
            return to_route('evaluation.list', $str)->with([
                'str' => 'success',
                'msg' => 'Validation réussie. En attente de traitement !'
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }
    }


    public function editNot(string $str)
    {
        try {
            list($classe, $evaluat) = explode('_', $str);
            $evaluated = $this->service->evaluated($evaluat);
            $existe = $this->service->verifyClasse(
                $evaluated->get_classe_id,
                $evaluated->level_matter_id
            );
            if(!$existe) {
                return to_route('evaluation.index')->with([
                    'str' => 'danger',
                    'msg' => 'Erreur, données incorrestes !'
                ]);
            }
            return view('pages.enseignant.evaluated.edit',[
                'evaluat' => $evaluated,
                'datas' => $this->service->getNotStudent($classe, $evaluat)
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
            $evaluat = $this->service->evaluated($id);
            $sub = $evaluat['sub_matter_id'] ? '-'.$evaluat['sub_matter']['symbol']:'';
            $matter = $evaluat['level_matter']['matter']['symbol'].$sub;
            $cutting = $evaluat['cutting_school_year']['cutting']['symbol'];
            $str = mt_rand(100, 1000).'_'.$id;
            $name = 'Fiche_Note_'.$evaluat['get_classe']['libelle'].'_'. $matter.'_'.$cutting.'_'.$str;
            return Excel::download(new NoteExport($evaluat['get_classe_id'], $id), $name.'.xlsx');
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function import(Request $request, string $str)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls|max:5120'
            ]);
            $file = $request->file('file');
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $explod = explode('_', $name);
            $evaluat = $this->service->evaluated($str);

            if(!(($explod[6] == $str) && ($explod[2] == $evaluat['get_classe']['libelle']))) {
                return back()->with([
                    'str' => 'danger',
                    'msg' => 'Erreur, Fichier Incompactible !'
                ]);
            }

            Excel::import(new NoteImport($str), $file);
            return to_route('note.show', $str)->with([
                'str' => 'success',
                'msg' => 'Importation réussie. En attente des traitement !'
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
            return [
                'exist' => $this->service->existNote($request['id']),
                'data' => $this->service->evaluated($request['id'])
            ];
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
                'evaluat' => 'required|exists:evaluateds,id',
                'type' => 'required|exists:evaluated_types,id',
                'note' => 'required|integer',
                'date' => 'required|date|before_or_equal:today',
                'subE' => 'nullable|integer',
                'status' => 'nullable|string',
            ]);
            list($id, $str) = explode('-',$id);
            if(!($request['evaluat'] == $id)) {
                return to_route('evaluation.show', $str)->with([
                    'str' => 'danger',
                    'msg' => 'Une erreur est survenue !'
                ]);
            }
            $result = $this->service->getUpdate($request);
            return to_route('evaluation.show', $str)->with([
                'str' => $result ? 'success':'danger',
                'msg' => $result ? 'Mise à jour éffectuée !':'Impossible de supprimer !'
            ]);
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
            $val = $request->validate([
                'id' => 'required|exists:evaluateds,id'
            ]);
            list($id, $str) = explode('-',$id);
            if(!($val['id'] == $id)) {
                return to_route('evaluation.show', $str)->with([
                    'str' => 'danger',
                    'msg' => 'Une erreur est survenue !'
                ]);
            }
            $result = $this->service->getDestroy($val['id']);
            return to_route('evaluation.show', $str)->with([
                'str' => $result ? 'success':'danger',
                'msg' => $result ? 'Evaluation supprimée':'Impossible de supprimer !'
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
