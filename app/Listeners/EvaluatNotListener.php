<?php

namespace App\Listeners;

use App\Events\EvaluatNotEvant;
use App\Services\GestionNoteService;
use App\Jobs\Matters\CalculMoyenneJob;
use App\Jobs\Matters\CalculSubMoyenneJob;
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
        if(! $evaluat['sub_matter_id']) {
            CalculMoyenneJob::dispatch(
                $evaluat['get_classe_id'], 
                $evaluat['level_matter_id'], 
                $evaluat['cutting_school_year_id'],
                $event->user
            );
        }
        else {
            CalculSubMoyenneJob::dispatch(
                $evaluat['get_classe_id'], 
                $evaluat['level_matter_id'], 
                $evaluat['cutting_school_year_id'],
                $evaluat['sub_matter_id'],
                $event->user
            );
        }
    }


    private function valNote($note, $valeur): string
    {
        if (blank($note)) {
            return 'nc';
        }

        $note = str_replace([' ', ','], ['', '.'], trim($note));

        if (!is_numeric($note)) {
            return 'nc';
        }

        $note = (float) $note;

        if ($note < 0 || $note > (20 * $valeur)) {
            return 'nc';
        }
        return $note < 10 ? '0' . $note : (string) $note;
    }
}
