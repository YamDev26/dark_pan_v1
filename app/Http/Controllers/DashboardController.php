<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }
    


    public function index()
    {
        try{

            if(!(auth()->check() && auth()->user()->status)){
                auth()->logout();
                return Redirect()->route('page.inactif')->with([
                    'str' => 'danger',
                    'msg' => 'Votre compte a été désactivé !'
                ]);
            }

            $this->service->updateCuttingDate();


            // dd(count($this->service->getTableTime()));

            return view('pages.dashboard',[
                'times' => $this->service->getTime(),
                'days' => $this->service->getDayWeek(),
                'data' => $this->service->getTableTime()
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'.$e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
