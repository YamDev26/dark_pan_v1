<?php

namespace App\Http\Controllers;

use App\Services\EnseigmntDevoirsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class EnseigmntDevoirsController extends Controller
{
    private $service;

    public function __construct(EnseigmntDevoirsService $service)
    {
        $this->service = $service;
    }
    
    public function index()
    {
        try { //dd($this->service->getDevoirsUser());
            return view('pages.enseignant.devoirs.index',[
                'data' => $this->service->getDevoirsUser(),
                'classes' => $this->service->getClasses(),
                'types' => $this->service->typeDevoirs(),
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
            return $this->service->getMatters($str);
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
            $valide = $request->validate([
                'cutting' => 'required|exists:cutting_school_years,id',
                'matter' => 'required|exists:level_matters,id',
                'classe' => 'required|exists:get_classes,id',
                'type' => 'required|exists:devoirs_types,id',
                'date' => 'required|date',
                'times' => 'required|string',
                'debut' => 'required|date_format:H:i',
            ]);

            $verify = $this->service->verifyTimeDevoirs(
                $valide['classe'], $valide['cutting'], $valide['date'], $valide['debut']
            );
            // dd($verify);
            if($verify) {
                return back()->with([
                    'str' => 'info',
                    'msg' => 'Pour cette date et l\'heure, un devoir déjà programmé.'
                ]);
            }

            $this->service->devoirSotre($valide);
            return back()->with([
                    'str' => 'success',
                    'msg' => 'Devoir programmé avec succes.'
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
            $data = $this->service->getDevoirs($id);
            return [
                'devoir' => ucfirst($data->devoirs_type->libelle),
                'classe' => $data->get_classe->libelle,
                'matter' => $data->level_matter->matter->symbol,
                'date' => date('d/m/Y', strtotime($data->dates)),
                'time' => $data->times,
                'debut' => $data->debut,
                'id' => $data->id 
            ];
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function delete(string $str)
    {
        try {
            $data = $this->service->getDevoirs($str);

            if($data['communs']) {
                return back()->with([
                    'str' => 'info',
                    'msg' => 'Vous n\'êtes pas autorisé pour sa suppression.'
                ]);
            }
            $this->service->destroy($data);
            return back()->with([
                'str' => 'success',
                'msg' => 'Suppression efectuée.'
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }


    public function EmploiTemps()
    {
        try {
            $pdf = Pdf::loadView('pdf.listes.emploi_temps_prof',[
                'times' => $this->service->getTime(),
                'days' => $this->service->getDayWeek(),
                'teacher' => $this->service->getUser(),
                'data' => $this->service->getTableTime(),
                'school' => $this->service->school(),
                'year' => $this->service->schoolYear(),
                'classe' => null
            ])
            ->setPaper('a4', 'landscape');
            return $pdf->stream('emploi_temps_prof.pdf');
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }
}
