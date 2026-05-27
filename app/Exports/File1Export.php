<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class File1Export implements FromView
{
    public function view(): View
    {
        return view('exports.file_1');
    }
}
