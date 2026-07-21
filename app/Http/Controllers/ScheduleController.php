<?php

namespace App\Http\Controllers;

use App\Services\ScheduleService;
use Barryvdh\DomPDF\Facade\Pdf;

class ScheduleController extends Controller
{
    private $service;

    public function __construct(ScheduleService $service)
    {
        $this->service = $service;
    }
    

    public function index()
    {
        try {
            return view('pages.horaires.index');
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
            return $this->service->getDataTable();
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
            return view('pages.horaires.detail',[
                'times' => $this->service->getTime(),
                'days' => $this->service->getDayWeek(),
                'teacher' => $this->service->getUser($id),
                'data' => $this->service->getTableTime($id),
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function generate(string $id)
    {
        try {
            $pdf = Pdf::loadView('pdf.listes.emploi_temps_prof',[
                'times' => $this->service->getTime(),
                'days' => $this->service->getDayWeek(),
                'teacher' => $this->service->getUser($id),
                'data' => $this->service->getTableTime($id),
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
                'msg' => 'Une erreur est survenue !'.$e->getMessage()
            ]);
        }
    }
}
