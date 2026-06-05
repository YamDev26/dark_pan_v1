<?php

namespace App\Listeners;

use App\Events\EvaluatNotEvant;
use App\Services\GestionNoteService;
use App\Jobs\Matters\CalculMoyenneJob;
use Illuminate\Contracts\Queue\ShouldQueue;

class EvaluatNotListener implements ShouldQueue
{

    public function handle(EvaluatNotEvant $event): void
    {
        $service = app(GestionNoteService::class);
        $evaluat = $service->evaluated($event->str);

        $note = $event->note;
        foreach($event->data as $i => $item) {
            $not = $this->valNote($note[$i], ($evaluat['value'] * 20));
            $service->noteEvaluat($item, $event->str, $not);
        }

        // Déclenchement de job pour le calcul de moyenne
        CalculMoyenneJob::dispatch(
            $evaluat['get_classe_id'], 
            $evaluat['level_matter_id'], 
            $evaluat['cutting_school_year_id']
        );
    }


    private function valNote($note, $valeur): string
    {
        $nots = 'nc';
        if(!blank($note)){
            if(is_int($note)){
                $nots = ($note <= $valeur) ? ($note < 10 ? '0'.$note:$note):'nc';
            }
            else{
                $not = str_replace([' ', ','], ['', '.'], $note);
                $nots = floatval($not) ? 
                (($note <= $valeur) ? 
                ($note < 10 ? '0'.$note:$note):'nc'):
                'nc';
            }
        }
        return $nots;
    }
}
