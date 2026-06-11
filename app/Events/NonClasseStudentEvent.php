<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class NonClasseStudentEvent
{
    use Dispatchable;

    public $student, $checked, $string;
    public function __construct($student, $checked, $string)
    {
        $this->student = $student;
        $this->checked = $checked;
        $this->string = $string;
    }
}
