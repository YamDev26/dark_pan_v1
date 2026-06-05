<?php

namespace App\Http\Controllers;

use App\Exports\NoteExport;
use App\Imports\NoteImport;
use App\Events\EvaluatNotEvant;
use App\Services\GestionNoteService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class GestionNoteController extends Controller
{
    protected $service;
    function __construct(GestionNoteService $service) {
        $this->service = $service;
    }
    
    public function index(string $str)
    {
        try {
            $explod = explode('-', $str);
            list($classe, $matter) = explode('_', $explod[1]);
            $nbre = $this->service->EvaluatedNbreMatter($explod[1], $explod[0]);
            return view('pages.notes.index',[
                'matter' => $this->service->matter($matter),
                'classe' => $this->service->classe($classe),
                'cutting' => $this->service->cutting($explod[0]),
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
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

    
    public function store(Request $request, string $str)
    {
        try {
            $valid = $request->validate([
                'str' => 'required|array',
                'str.*' => 'required|integer',
                'note' => 'required|array',
                'note.*' => 'nullable|integer',
                'evaluat' => 'required|exists:evaluateds,id',
            ]);

            if(!($valid['evaluat'] == $str)) {
                return back()->with([
                    'str' => 'danger',
                    'msg' => 'Une erreur est survenue !'
                ]);
            }

            EvaluatNotEvant::dispatch($valid['str'], $valid['note'], $str);
            return to_route('note.show', $str)->with([
                'str' => 'success',
                'msg' => 'Validation réussie. En attente de traitement !'
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
            return view('pages.notes.detail',[
                'evaluat' => $this->service->evaluated($id),
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

    
    public function edit(string $id)
    {
        try {
            return view('pages.notes.edit',[
                'evaluat' => $this->service->evaluated($id),
                'datas' => $this->service->getNotStudent($id)
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
        //
    }

    
    public function destroy(string $id)
    {
        //
    }
}
