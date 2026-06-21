<?php

namespace App\Http\Controllers;

use App\Services\StatistikService;
use Illuminate\Http\Request;

class StatistikController extends Controller
{
    private const TOTAL = 'total';  private const CYCLE_1 = 'cycle1'; private const CYCLE_2 = 'cycle2';
    private $service;

    public function __construct(StatistikService $service)
    {
        $this->service = $service;
    }
    
    public function index($str)
    {
        return view('pages.statistiks.index',[
            'cycle1' => $this->service->getResultatCycle1($str),
            'cycle2' => $this->service->getResultatCycle2($str),
            'total' => $this->service->getStatistikTotal($str),
            'result1' => $this->service->getResultat($str, self::CYCLE_1),
            'result2' => $this->service->getResultat($str, self::CYCLE_2),
            'result3' => $this->service->getResultat($str, self::TOTAL),
        ]);
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
