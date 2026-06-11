<?php

namespace App\Listeners;

use App\Services\MoyenneService;
use App\Events\NonClasseStudentEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class NonClasseStudentListener implements ShouldQueue
{
    public function handle(NonClasseStudentEvent $event): void
    {
        $service = app(MoyenneService::class);

        $data = $event->student;
        $checked = $event->checked;
        list($class, $cutting) = explode('_', $event->string);
        $classe = $service->getClasse($class);

        foreach($data as $item) {

            if(in_array($item, $checked)) {
                $service->updateMoyenne($item, $cutting, $classe['level_id']);
            }

        }

    }
}
