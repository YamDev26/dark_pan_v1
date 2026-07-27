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
        try {
            return view('pages.statistiks.index',[
                'cutting' => $this->service->getCutting($str),
                'close' => $this->service->getCloseCutting($str),
                'cycle1' => $this->service->getResultatCycle1($str),
                'cycle2' => $this->service->getResultatCycle2($str),
                'total' => $this->service->getStatistikTotal($str),
                'result1' => $this->service->getResultat($str, self::CYCLE_1),
                'result2' => $this->service->getResultat($str, self::CYCLE_2),
                'result3' => $this->service->getResultat($str, self::TOTAL),
            ]);
        }
        catch (\Exception $e) {
            return back()->with([
                'str' => 'danger',
                'msg' => 'Une erreur est survenue !'
            ]);
        }
    }

    
    public function store(string $str)
    {
        try {
            $result = $this->service->storeCuttingClose($str);
            return back()->with([
                'str' => $result['str'],
                'msg' => $result['msg']
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
